/*
  1078488011
  $('body').html('<script type="text/ace-instagram">{num:9,query:"users/227962011/media/recent",width:125}</script>'); $.getScript('http://local.hopechapellongbeach.com/assets/ace.js');
*/


ace = {

  config: {
    readyCheckDelay: 15
  }
  ,_readyCbs: []
  ,_ready: false

  ,init: function(){
    var z = this;
    if (z.inited)
      return false;
    z.inited = true;

    (function checkReady(){
      if (!window.$)
        return setTimeout(checkReady,z.config.readyCheckDelay);
      console.log('ace ready');
      z._ready = true;
      $.each(z._readyCbs,function(i,cb){
        if (cb instanceof Function)
          cb();
      });
      delete z._readyCbs;
      z.ui.checkForWidgets();
    }());
  }

  ,ready: function(cb){
    if (this._ready)
      cb();
    else
      this._readyCbs.push(cb);
  }

  ,ui: {
    _modules: {}

    ,register: function(key,proto){
      var z = this
      ,module;
      if (z.getModule(key))
        return console.log(key+' already registered');
      ace.ready(function(){
        module = z._modules[key] = new Function();
        $.extend(true,module.prototype,{
          init: function(){}
          ,opts: {}
        },proto,{
          key: key
          ,cssKey: 'ace-'+key
        });
        module.instances = [];
        ace.evt.trigger(key+':registered');
      });
    }

    ,getModule: function(key){
      return this._modules[key];
    }

    ,moduleReady: function(moduleKey,cb){
      var z = this;
      ace.evt.ready(moduleKey+':registered',function(){
        var module = z.getModule(moduleKey)
        ,i = 0
        ,deps
        ;
        if (!(module.config && module.config.dependencies && module.config.dependencies instanceof Array && module.config.dependencies.length))
          return depsRdy();
        deps = module.config.dependencies;
        (function next(){
          ace.evt.ready(deps[i]+':registered',function(){
            if (++i == deps.length)
              return depsRdy();
            next();
          });
        }());
        function depsRdy(){
          ace.ready(cb);
        }
      });
    }

    ,checkForWidgets: function(jCont){
      var z = this;
      $(function(){
        console.log('found',(jCont || $('body')).find('script[type^="text/ace-"]'));
        (jCont || $('body')).find('script[type^="text/ace-"]').each(function(){
          var $script = $(this)
          ,key = $script.attr('type').replace('text/ace-','')
          ,opts
          ;
          try {
            opts = eval('('+$.trim($script[0].innerHTML)+')');
          } catch (e) {}
          if (typeof opts != 'object')
            opts = {};
          z.widgetize(key,$script,opts);
        });
      });
    }

    ,widgetize: function(key,$cont,opts,cb){
      var z = this;
      z.moduleReady(key,function(){
        var $elm = $('<div class="ace-'+key+'"></div>')
        ,module = z.getModule(key)
        ,instance = new module();
        ;
        $cont.replaceWith($elm);
        instance.opts = $.extend(true,{},module.prototype.opts,opts);
        instance.$ = {
          cont: $elm
        };
        module.instances.push(instance);
        instance.init();
        if (cb)
          cb.call(instance);
      });
    }

  }

  ,evt: {
    _evts: {}

    ,on: function(key,cb){
      var evt = this._getEvt(key)
      evt.subs.push({
        cb: cb
      });
    }

    ,ready: function(key,cb){
      var evt = this._getEvt(key);
      if (evt.fired_once) {
        cb(evt.error,evt.data);
      } else {
        evt.subs.push({
          cb: cb,
          type_ready: true
        });
      }
    }

    ,off: function(key,cb){
      var z = this
        ,evt;
      if (!z._evts[key]) return;
      evt = z._getEvt(key);
      if (typeof(cb) == 'undefined') {
        evt.subs = [];
      } else {
        $.each(evt.subs,function(i,sub){
          // checking !sub in case this is called in callback inside fireSubs
          if (!sub || sub.cb == cb)
            evt.subs[i] = null;
        });
        ace.util.arrayFilter(evt.subs,function(sub){
          return sub !== null;
        });
      }
    }

    ,trigger: function(key,error,data){
      var evt = this._getEvt(key);
      evt.fired_once = true;
      evt.error = error;
      evt.data = data;
      this._fireSubs(key);
    }

    ,_getEvt: function(key){
      if (typeof(this._evts[key]) == 'undefined') {
        this._evts[key] = {
          subs: []
        };
      }
      return this._evts[key];
    }

    ,_fireSubs: function(key){
      var evt = this._getEvt(key);
      $.each(evt.subs.slice(0),function(i,sub){
        sub.cb(evt.error,evt.data);
      });
      $.each(evt.subs,function(i,sub){
        if (sub.type_ready)
          evt.subs[i] = null;
      });
      ace.util.arrayFilter(evt.subs,function(sub){
        return sub !== null;
      });
    }

  }

  ,util: {
    strToClass: function(str){
      return str.replace(/(^[^a-zA-Z]+)|([^a-zA-Z0-9_\-])/g,'');
    }

    ,rand: function(min,max){
      return min+Math.round(Math.random()*(max-min));
    }

    ,capitalize: function(str){
      var words = str.split(' ')
        i,c;
      for (i=0,c=words.length;i<c;++i) {
        if (words[i])
          words[i] = words[i].charAt(0).toUpperCase() + words[i].substr(1);
      }
      return words.join(' ');
    }

    ,formatPlace: function(num){
      var numPos = Math.abs(num)
        ,lastChar = (num+'').split('').pop()
        ,suffix
      ;
      if (num == 0) return '0th';
      if (numPos > 10 && numPos < 20) suffix = 'th';
      else if (lastChar == 1) suffix = 'st';
      else if (lastChar == 2) suffix = 'nd';
      else if (lastChar == 3) suffix = 'rd';
      else suffix = 'th';
      return num+suffix;
    }

    ,formatInteger: function(num){
      var pieces = (num+'').match(/^(\-?)([0-9]+)(.*)/)
        ,chars,i,c;        
      if (!pieces || !pieces[2])
        return num;
      chars = pieces[2].split('');
      for (i=3,c=chars.length;i<c;i=i+3)
        chars[c-i-1] += ',';
      return pieces[1] + chars.join('') + (pieces[3] ? pieces[3] : '');
    }

    ,getViewportScrollY: function(){
      return (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
    }

    ,trueDim: function(jelm){
      var nre = /[^0-9\-.]/g
      ,d = {
        w: jelm.width()
        ,h: jelm.height()
      },add,i,c
      ;
      add = ['border-left-width','padding-left','padding-right','border-right-width'];
      for (i=0,c=add.length;i<c;++i) {
        d.w += +(jelm.css(add[i])||'0').replace(nre,'');
      }
      add = ['border-top-width','padding-top','padding-bottom','border-bottom-width'];
      for (i=0,c=add.length;i<c;++i) {
        d.h += +(jelm.css(add[i])||'0').replace(nre,'');
      }
      return d;
    }

    ,arrayFilter: function(arr,cb,start){
      var i,c;
      start = typeof(start) == 'number' ? start : 0;
      for (i=start,c=arr.length;i<c;++i) {
        if (!cb(arr[i])) {
          arr.splice(i,1);
          this.arrayFilter(arr,cb,i);
          break;
        }
      }
      return arr;
    }
  }

};

ace.ui.register('instagram',{
  opts: {
    clientId: 'a26e3cd4b7b24a50857f54f78f051b63'
    ,url: 'https://api.instagram.com/v1/'
    ,type: 'squares med'
    ,num: 12
  }
  ,init: function(){
    var z = this;
    z.getData(function(){
      z.build();
      z.functionalize();
    });
  }
  ,getData: function(cb){
    var z = this;
    $.getJSON(z.opts.url+z.opts.query+'?callback=?',{
      client_id: z.opts.clientId
      ,count: z.opts.num
    },function(data){
      if (!(data && data.data))
        return console.log('instagram api error');
      z.media = data.data;
      cb();
    });
  }
  ,build: function(){
    var z = this
    ,x = z.cssKey
    ;
    $.each(z.opts.type.split(' '),function(i,t){
      z.$.cont.addClass('type-'+t);
    });
    $.each(z.media,function(i,m){
      var jImg = $('<div class="'+x+'-img">'
        + '<img class="'+x+'-img-img" src="'+m.images.low_resolution.url+'" />'
      + '</div>');
      jImg.attr('alt',m.caption.text);
      z.$.cont.append(jImg);
    });
    z.$.cont.append('<div class="clear">&nbsp;</div>');

    z.$.imgs = z.$.cont.find('div.'+x+'-img');
  }
  ,functionalize: function(){
    var z = this
    ,x = z.cssKey
    ;

    if (z.opts.hoverFadeIn) {
      z.$.imgs.fadeTo(0,.8);
      z.$.imgs.bind('mouseover mouseout',function(e){
        var t = $(this);
        if (e.type == 'mouseover')
          t.stop().fadeTo(100,1);
        else
          t.stop().fadeTo(100,.8);
      });
    }
  }
});

ace.init();
