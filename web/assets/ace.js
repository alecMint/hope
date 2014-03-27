/*
  1078488011
  $('body').html('<script type="text/ace-instagram">{num:9,query:"users/227962011/media/recent",width:125}</script>'); $.getScript('http://local.hopechapellongbeach.com/assets/ace.js');

<script type="text/ace-carousel">{
  imgs: [
    'http://www.cleanenergyflorida.org/wp-content/uploads/2014/03/trees.jpg'
    ,'http://www.ecologistblog.com/wp-content/uploads/2013/09/trees.jpg'
    ,'http://nickjones.me/wp-content/uploads/2013/07/fruit-trees-spring.jpg'
    ,'http://naturespicwallpaper.com/wp-content/uploads/2014/02/trees8.jpg'
    ,'http://img.timeinc.net/time/photoessays/2008/trees/franklin_trees_01.jpg'
    ,'http://siliconangle.com/files/2013/07/Trees.jpg'
  ]
}</script>
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
      z._jQExtensions();
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
          ,log: function(){
            var args = [this.key];
            $.each(arguments,function(i,v){
              args.push(v);
            });
            console.log.apply(console,args);
          }
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

    ,escapeHtml: function(str,nl2br){
      str = $('<div>').text(str).html();
      if (nl2br)
        str = str.replace(/\n/g,'<br />')
      return str;
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

    ,getImageToWindowFit: function(windowSize,imgSize,center){
      /**
        ex:
          center = [null,50/100]
          center = [0.2,0.8]
      **/
      windowSize = [+windowSize[0],+windowSize[1]];
      imgSize = [+imgSize[0],+imgSize[1]];
      if (!center) center = [null,null];
      center = [center[0] === null ? null : +center[0],center[1] === null ? null : +center[1]];

      var window_w2h = windowSize[0]/windowSize[1],
        img_w2h = imgSize[0]/imgSize[1],
        offsetX = offsetY = 0,
        newWidth,newHeight,fit;

      if (window_w2h > img_w2h) {
        newWidth = windowSize[0];
        newHeight = newWidth/img_w2h;
        if (newHeight < windowSize[1]) newHeight = windowSize[1];
        offsetY = -1 * (newHeight-windowSize[1])/2;
        if (center[1] !== null) offsetY += (.5-center[1])*newHeight;
        if (offsetY > 0) offsetY = 0;
        else if (offsetY < windowSize[1]-newHeight) offsetY = windowSize[1]-newHeight;
      } else {
        newHeight = windowSize[1];
        newWidth = newHeight*img_w2h;
        if (newWidth < windowSize[0]) newWidth = windowSize[0];
        offsetX = -1*(newWidth-windowSize[0])/2;
        if (center[0] !== null) offsetX += (.5-center[0])*newWidth;
        if (offsetX > 0) offsetX = 0;
        else if (offsetX < windowSize[0]-newWidth) offsetX = windowSize[0]-newWidth;
      }

      fit = {
        width: newWidth,
        height: newHeight,
        offset: {
          x: offsetX,
          y: offsetY
        }
      };
      fit.style = 'width:'+fit.width+'px;height:'+fit.height+'px;left:'+fit.offset.x+'px;top:'+fit.offset.y+'px;';
      fit.css = {
        width: fit.width+'px',
        height: fit.height+'px',
        left: fit.offset.x+'px',
        top: fit.offset.y+'px'
      };
      return fit;
    }

  }

  ,_jQExtensions: function(){
    $.fn.imagesLoaded = function(cb){
      var $elm = this
        ,jimgs = this.find('img').andSelf().filter('img')
        ,imgs = []
        ,numLoaded = 0
        ,numImgs,loaded
      ;
      numImgs = jimgs.length;
      if (!numImgs)
        return done();
      loaded = function(index){
        if (!imgs[index]) {
          imgs[index] = true;
          if (++numLoaded == numImgs)
            done();
        }
      };
      jimgs.each(function(index){
        var doIt = function(){
          loaded(index);
        };
        imgs.push(false);
        if (this.complete) {
          doIt();
        } else {
          $(this).bind('load',doIt).bind('error',doIt);
          if (this.complete)
            doIt();
        }
      });
      function done(){
        if (cb) {
          setTimeout(function(){
            cb.call($elm);
          },0);
        }
      }
    }
    $.fn.widgetize = function(widgetName, opts){
      ace.ui.widgetize(widgetName, this, opts);
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
      z.$.imgs.fadeTo(0,.5);
      z.$.imgs.bind('mouseover mouseout',function(e){
        var t = $(this);
        if (e.type == 'mouseover')
          t.stop().fadeTo(200,1);
        else
          t.stop().fadeTo(200,.5);
      });
    }
  }
});


ace.ui.register('carousel',{
  opts: {
    imgs: []
    ,dims: '200x150'
    ,speed: 300
    ,shadbox: true
  }
  ,slideQueue: []
  ,init: function(){
    var z = this
    ,d = z.opts.dims.split('x')
    ;
    z.imgWidth = +d[0];
    z.imgHeight = +d[1];

    if (!z.opts.imgs.length) {
      z.$.cont.css('display','none');
      return z.log('missing imgs');
    }

    z.build();
    z.calcDims();
    z.buildSlides();
    z.functionalize();
  }
  ,build: function(){
    var z = this
    ,x = z.cssKey
    ;
    z.$.cont.html('<div class="'+x+'-mask">'
        + '<div class="'+x+'-slide_cont"></div>'
      + '</div>'
      +'<div class="'+x+'-arr '+x+'-arr-left" xdata-dir="-1" style="display:none;"></div>'
      +'<div class="'+x+'-arr '+x+'-arr-right" xdata-dir="1" style="display:none;"></div>'
    );
    z.$.mask = z.$.cont.find('div.'+x+'-mask');
    z.$.slideCont = z.$.mask.find('div.'+x+'-slideCont');
    z.$.arrows = z.$.cont.find('div.'+x+'-arr');
  }
  ,calcDims: function(){
    var z = this
    ,x = z.cssKey
    ,td = ace.util.trueDim(z.$.slides.eq(0).find('div.'+x+'-img').eq(0));
    z.itemWidth = td.w;
    z.itemHeight = td.h;
    z.slideWidth = z.opts.imgs.length*z.itemWidth;
    z.maskWidth = z.$.mask.width();
    z.slideDistance = Math.floor(z.maskWidth/z.itemWidth)*z.itemWidth;
  }
  ,buildSlides: function(){
    var z = this;
    z.$.slides = z.createSlide();
    z.$.mask.append(z.$.slides);
    z.$.mask.css('height',z.itemHeight+'px');

    if (z.slideWidth > z.maskWidth) {
      // need arrow functionality
      z.$.slides.add(z.createSlide()).add(z.createSlide());
      z.$.arrows.css('display','');
    }
    z.$.slides.css('visibility','');
  }
  ,createSlide: function(){
    var z = this
    ,x = z.cssKey
    ,jSlide
    ;
    if (z.$.slides && z.$.slides.length)
      return z.$.slides.eq(0).clone(true);
    jSlide = $('<div class="'+x+'-slide" style="visibility:hidden;"></div>')
    $.each(z.opts.imgs,function(i,src){
      var jImg = $('<div class="'+x+'-img is-loading">'
        + '<div class="'+x+'-img-wrap">'
          + '<img class="'+x+'-img-img" alt="" src="'+src+'" />'
        + '</div>'
      + '</div>').css({
        width: z.imgWidth+'px'
        ,height: z.imgHeight+'px'
        ,left: (i*z.itemHeight)+'px'
      }).imagesLoaded(function(){
        var jImgImg = t.find('img.'+x+'-img-img');
        jImg.removeClass('is-loading');
        jImgImg.css(
          ace.util.getImageToWindowFit([z.imgWidth,z.imgHeight],[jImgImg[0].width,jImgImg[0].height]).css
        );
      });
      jSlide.append(jImg);
    });
  }
  ,functionalize: function(){
    var z = this
    ,x = z.cssKey
    ;

    z.$.arrows.bind('click',function(){
      z.slide(+$(this).attr('xdata-dir'));
    });

    if (z.opts.shadbox) {
      z.$.cont.addClass('shadbox_enabled');
      z.$.slides.find('img.'+x+'-img-img').bind('click',function(){
        ace.shadbox($(this).attr('src'));
      });
    }
  }
  ,slide: function(dir){
    var z = this;

    //z.slideQueue.push(dir);
    // if they click a direction opposite of current animation lets clear the queue
    if (z.slideQueue[0] && z.slideQueue[0] != dir)
      z.slideQueue = [z.slideQueue[0],dir]
    else
      z.slideQueue.push(dir);

    if (z.slideQueue.length == 1)
      slide();

    function slide() {
      var dir = z.slideQueue[0];
      if (!dir)
        return;
      z.$.slidesCont.animate({
        left: (z.$.slidesCont.position().left-(dir*z.slideDistance))+'px'
      },{
        duration: z.opts.speed
        ,complete: function(){
          var offsetX = z.$.slidesCont.position().x;
          z.$.slides.sort(function(a,b){
            return $(a).position().left-$(b).position().left;
          });
          if (z.$.slides.eq(0).position().left+offsetX + z.slideWidth < -z.slideDistance) {
            // push onto end
            z.$.slides.eq(0).css('left', (z.$.slides.eq(2).position().left+z.slideWidth)+'px');
          } else if (z.$.slides.eq(2).position().left+offsetX > z.maskWidth+z.slideDistance) {
            // pop into beginning
            z.$.slides.eq(2).css('left', (z.$.slides.eq(0).position().left-z.slideWidth)+'px');
          }
          z.slideQueue.shift();
          slide();
        }
      });
    }
  }
});


ace.shadbox = function(src,opts,cb){
  var z = ace.shadbox
  ,opts_ = $.extend({},typeof(opts)=='object'?opts:{},z.config.defaults)
  ,cb_ = cb ? cb : (opts instanceof Function ? opts : null)
  ;

  z.close();
  if (!z.$.cont)
    z.build();

  z.open(src,opts_,cb_);
};
ace.shadbox.config = {
  cssKey: 'amint-shadbox'
  ,defaults: {
    viewport: {
      padding: {x:.05, y:.05}
    }
    ,anim: {
      fadeSpeed: 100
      ,delay: 300
      ,contentExpandSpeed: 300
      ,contentFadeInSpeed: 300
    }
  }
}
ace.shadbox.$ = {};
ace.shadbox.build = function(){
  var z = this
    ,x = z.config.cssKey
  ;
  z.$.cont = $('<div class="'+x+'" style="display:none;">'
    + '<div class="'+x+'-bg"></div>'
    + '<div class="'+x+'-content">'
      + '<div class="'+x+'-content-item"></div>'
      + '<a class="'+x+'-close" href="#"></a>'
    + '</div>'
  + '</div>');
  $('body').append(z.$.cont);
  z.$.bg = z.$.cont.find('div.'+x+'-bg');
  z.$.content = z.$.cont.find('div.'+x+'-content');
  z.$.contentItem = z.$.content.find('div.'+x+'-content-item');
  z.$.close = z.$.content.find('a.'+x+'-close');

  z.$.cont.bind('click',function(){
    z.close();
  });
  z.$.close.bind('click',function(e){
    e.preventDefault();
  });
}
ace.shadbox.open = function(src,opts,cb){
  var z = this
    ,d = $(document)
    ,w = $(window)
    ,viewportWidth = w.width()
    ,viewportHeight = w.height()
    ,paddingX = viewportWidth*opts.viewport.padding.x
    ,paddingY = viewportHeight*opts.viewport.padding.y
    ,initialX = (viewportWidth-z.$.content.width())/2
    ,initialY = (viewportHeight-z.$.content.height())/2 + ace.util.getViewportScrollY()
    ,img
  ;
  console.log(viewportWidth,viewportHeight,paddingX,paddingY,initialX,initialY,ace.util.getViewportScrollY());
  z.isOpening = true;
  z.$.content.addClass('loading').css({
    width: ''
    ,height: ''
  });
  z.$.content.css({
    left: initialX+'px'
    ,top: initialY+'px'
  });
  z.$.cont.css({
    width: d.width()+'px'
    ,height: d.height()+'px'
  }).fadeIn(opts.anim.fadeSpeed);
  img = new Image;
  $(img).bind('load',function(){
    var viewWidth = viewportWidth-paddingX
    ,viewHeight = viewportHeight-paddingY
    ,imgRatio,viewRatio,targetWidth,targetHeight,targetX,targetY
    ;
    if (img.width > viewWidth || img.height > viewHeight) {
      imgRatio = img.width/img.height;
      viewRatio = viewWidth/viewHeight;
      if (viewRatio < imgRatio) {
        targetWidth = viewWidth;
        targetHeight = targetWidth/imgRatio;
      } else {
        targetHeight = viewHeight;
        targetWidth = targetHeight*imgRatio;
      }
    } else {
      targetWidth = img.width;
      targetHeight = img.height;
    }
    targetX = (viewportWidth-targetWidth)/2;
    targetY = (viewportHeight-targetHeight)/2 + ace.util.getViewportScrollY();
    jImg = $('<img src="'+src+'" alt="" />').fadeTo(0,0);
    z.$.contentItem.append(jImg);
    z.$.content.delay(opts.anim.delay).animate({
      width: targetWidth+'px'
      ,height: targetHeight+'px'
      ,left: targetX+'px'
      ,top: targetY+'px'
    },{
      duration: opts.anim.contentExpandSpeed
      ,complete: function(){
        z.$.content.removeClass('loading');
        jImg.fadeTo(opts.anim.contentFadeInSpeed,1,function(){
          z.isOpening = false;
          z.isOpen = true;
          if (cb)
            cb();
        });
      }
    });
  }).bind('error',function(){
    z.isOpening = false;
    z.isOpen = true;
    z.$.content.removeClass('loading').addClass('error').css({
      left: (viewportWidth-z.$.content.width())/2 + 'px'
      ,top: ((viewportHeight-z.$.content.height())/2 + ace.util.getViewportScrollY()) + 'px'
    });
    if (cb)
      cb();
  });
  img.src = src;
}
ace.shadbox.close = function(){
  var z = this;
  z.isOpen = z.isOpening = false;
  if (z.$.cont) {
    z.$.cont.css('display','none');
    z.$.content.removeClass('error loading').css({
      width: ''
      ,height: ''
      ,left: ''
      ,top: ''
    });
    z.$.contentItem.empty();
  }
}


ace.init();
