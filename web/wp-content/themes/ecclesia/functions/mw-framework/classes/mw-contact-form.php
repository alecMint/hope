<?php if (!defined('MW_THEME')) die('No direct script access allowed');

class MW_Contact_Form
{    
    public $formOp = array();
    public $error = array(
        'flag' => FALSE,
        'form_error' => '',
        'fields' => array(),
    );
    public $input = array();
    public $sent = FALSE;
    
    public function __construct ( $formOp )
    {
        $this->formOp = $formOp;
    }
    
    public function isPosted ()
    {
        return isset($_POST[ $this->formOp['form_id'] ]) ? TRUE : FALSE;
    }
    
    public function getUserInput ()
    {
        foreach ( $this->formOp['fields'] as $f ) {
            $this->input[$f['name']] = isset($_POST[$f['name']]) ? $_POST[$f['name']] : '';
        }
    }
    
    public function send ()
    {
        $message = '';
        foreach ( $this->input as $f_name => $f_value ) {
            if ( $f_name != 'contact_message' )  {
                $f_name = ucfirst(str_replace('contact_', '', $f_name));
                $message .= "{$f_name} : {$f_value}<br>\r\n";
            }
        }
        $message .= "--------------------------------------------------------------<br>\r\n";
        $message .= $this->input['contact_message'];
        
        $emailTo = $this->formOp['emailTo'];
        
        if ( !empty($this->formOp['subject']) ) {
            $subject = $this->formOp['subject'];
            if ( isset($this->input['contact_subject']) )
                $subject .= ' ' . $this->input['contact_subject'];
        } else {
            if ( !empty($this->input['contact_subject']) )
                $subject = $this->input['contact_subject'];
            else
                $subject = __('Message from contact form', 'mw_frm');
        }
        
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        
        if ( isset($this->input['contact_name']) && isset($this->input['contact_email']) ) {
            $headers .= "From: {$this->input['contact_name']} <{$this->input['contact_email']}>\r\n";
            $headers .= "Reply-To: {$this->input['contact_email']}\r\n";
        } elseif ( isset($this->input['contact_email']) ) {
            $headers .= "From: {$this->input['contact_email']} <{$this->input['contact_email']}>\r\n";
        } else {
            $fromEmail = get_bloginfo('admin_email');
            $headers .= "From: {$fromEmail} <{$fromEmail}>\r\n";
        }
        
        $sent = mail($emailTo, $subject, $message, $headers);
        if ($sent) {
            $this->sent = TRUE;
            return TRUE;
        } else {
            $this->sent = FALSE;
            return FALSE;
        }
    }
    
    public function validate ()
    {
        $this->error = array('flag' => FALSE);
        
        if (!wp_verify_nonce($_POST['_wpnonce'], $this->formOp['nonce'])) {
            $this->error['form_error'] = __('Detected hacking attempt.', 'mw_frm');
            return FALSE;
        }
        
        $in = $this->input;
        foreach ( $this->formOp['fields'] as $field ) {
            extract($field);
            if (isset($rules)) {
                if ( in_array('required', $rules) && empty($in[$name]) ) { // if required
                    $this->error['flag'] = TRUE;
                    $this->error['fields'][$name] = __('This field is required.', 'mw_frm');
                } elseif ( in_array('email', $rules) && (empty($in[$name]) || !$this->isEmail($in[$name])) ) { // if email field
                    $this->error['flag'] = TRUE;
                    $this->error['fields'][$name] = __('Please enter a valid email address.', 'mw_frm');
                }
            }
        }
        
        return $this->error['flag'] === TRUE ? FALSE : TRUE; // error flag is TRUE - form invalid, else form valid
    }
    
    public function outputResultAjax ()
    {
        if ( $this->sent === TRUE ) {
            printf( '<div class="ok-box">%s</div>', wptexturize(wpautop(stripslashes($this->formOp['thanks_msg']))) );
            return;
        }
        
        if ( !empty($this->error['form_error']) )
            printf( '<div class="warning-box">%s</div>', wptexturize(wpautop($this->error['form_error'])) );
        
        if ( isset($this->error['flag']) && $this->error['flag'] === TRUE )
            printf( '<div class="warning-box">%s</div>', wptexturize(wpautop(stripslashes($this->formOp['error_msg']))) );
    }
    
    public function outputForm ()
    {
        if ( $this->sent === TRUE ) {
            printf( '<div class="ok-box">%s</div>', wptexturize(wpautop(stripslashes($this->formOp['thanks_msg']))) );
            return;
        }
        
        if ( !empty($this->error['form_error']) )
            printf( '<div class="warning-box">%s</div>', wptexturize(wpautop($this->error['form_error'])) );
        
        if ( isset($this->error['flag']) && $this->error['flag'] === TRUE )
            printf( '<div class="warning-box">%s</div>', wptexturize(wpautop(stripslashes($this->formOp['error_msg']))) );
        
        $ajaxClass = $this->formOp['ajax'] ? ' ajax-form' : '';
        printf( '<form action="%s" id="%s" method="post" class="contact-form%s">', get_permalink(), $this->formOp['form_id'], $ajaxClass );
        
        foreach ( $this->formOp['fields'] as $field ) {
            extract($field);
            $html = '';
            $idAttr = 'mw-contact-' . $name;
            $validClass = count($rules>0) ? ' ' . implode(' ', $rules) : '';
            
            $html .= form_label ($label . (in_array('required', $rules) ? ' <span class="req-mark">*</span>' : ''), $idAttr, NULL, TRUE);
            if ($type == 'input')
                $html .= form_input( $name, $_POST[$name], array('id'=>$idAttr, 'class'=>'text'.$validClass), TRUE );
            elseif ($type == 'textarea')
                $html .= form_textarea( $name, $_POST[$name], array('id'=>$idAttr, 'class'=>$validClass, 'rows'=>'7', 'cols'=>'40'), TRUE);
            else
                continue;
            if (isset($this->error['fields'][$name]))
                $html .= form_label ($this->error['fields'][$name], $idAttr, array('class'=>'error'), TRUE);
            printf( '<div class="form-element">%s</div>', $html );
        }
        
        echo '<p>' . __('<span class="req-mark">*</span> Required field', 'mw_frm') . '</p>';
        form_hidden( $this->formOp['form_id'], 1);
        form_hidden( 'action', $this->formOp['ajax_action'] );
        wp_nonce_field( $this->formOp['nonce'] );
        printf( '<div class="form-element">%s</div>', form_submit( 'submit', $this->formOp['submit_label'], array('class'=>'submit'), TRUE ) );
        
        echo '</form>';
    }
    
    private function isEmail ( $email )
    {
        return( preg_match('/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$/i', trim($email)) );
    }

}