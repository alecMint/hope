/*
  1078488011
  $('body').html('<script type="text/ace-instagram">{num:9,query:"users/227962011/media/recent",width:125}</script>'); $.getScript('http://local.hopechapellongbeach.com/assets/ace.js');

  $('.grid-4:first').html('<div>').find('div').widgetize('carousel',{
    imgs: [
      'http://www.cleanenergyflorida.org/wp-content/uploads/2014/03/trees.jpg'
      ,'http://crazy-frankenstein.com/free-wallpapers-files/seasonal-wallpapers/spring-trees-wallpapers/spring-trees-2-wallpapers-1680x1050.jpg'
      ,'http://nickjones.me/wp-content/uploads/2013/07/fruit-trees-spring.jpg'
      ,'http://naturespicwallpaper.com/wp-content/uploads/2014/02/trees8.jpg'
      ,'http://upload.wikimedia.org/wikipedia/commons/8/84/Autumn_trees_in_Dresden.jpg'
      ,'http://siliconangle.com/files/2013/07/Trees.jpg'
    ]
  });
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
    ,dims: '203x152'
  }
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
    z.$.cont.addClass('is-loading');
    z.$.mask.imagesLoaded(function(){
      z.calcDims();
      z.position();
      z.functionalize();
      z.$.cont.removeClass('is-loading');
    });
  }
  ,build: function(){
    var z = this
    ,x = z.cssKey
    ;
    z.$.cont.html('<div class="'+x+'-mask" style="height:'+z.imgHeight+'px;">'
        + '<div class="'+x+'-slide '+x+'-slide0"></div>'
        + '<div class="'+x+'-slide '+x+'-slide1"></div>'
      + '</div>'
      +'<div class="'+x+'-arr '+x+'-arr-left" xdata-dir="-1" style="display:none;"></div>'
      +'<div class="'+x+'-arr '+x+'-arr-right" xdata-dir="1" style="display:none;"></div>'
    );
    z.$.mask = z.$.cont.find('div.'+x+'-mask');
    z.$.slide0 = z.$.cont.find('div.'+x+'-slide0').css('visibility','hidden');
    z.$.slide1 = z.$.cont.find('div.'+x+'-slide1').css('display','none');
    z.$.slides = z.$.slide0.add(z.$.slide1);
    z.$.arrows = z.$.cont.find('div.'+x+'-arr');
    $.each(z.opts.imgs,function(k,v){
      var html = '<div class="'+x+'-img" style="width:'+z.imgWidth+'px;height:'+z.imgHeight+'px;">'
        + '<div class="'+x+'-img-wrap">'
          + '<img class="'+x+'-img-img" alt="" src="'+v+'" />'
        + '</div>'
      + '</div>';
      z.$.slide0.append(html);
      z.$.slide1.append(html);
    });
    z.$.slide0Imgs = z.$.slide0.find('div.'+x+'-img');
    z.$.slide1Imgs = z.$.slide1.find('div.'+x+'-img');
  }
  ,calcDims: function(){
    var z = this
    ,x = z.cssKey
    ,td = ace.util.trueDim(z.$.slide0.find('div.'+x+'-img').eq(0));
    z.itemWidth = td.w;
    z.itemHeight = td.h;
  }
  ,position: function(){
    var z = this
    ,x = z.cssKey
    ,slideWidth = z.opts.imgs.length*z.itemWidth
    ;

    z.$.mask.css('height',z.itemHeight+'px');

    $.each(z.opts.imgs,function(i){
      var jItems = z.$.slide0Imgs.eq(i).add(z.$.slide1Imgs.eq(i)).css('left',(i*z.itemWidth)+'px')
      ,jImgs = jItems.find('img.'+x+'-img-img')
      ;
      jImgs.css(
        ace.util.getImageToWindowFit([z.imgWidth,z.imgHeight],[jImgs[0].width,jImgs[0].height]).css
      );
    });

    //z.log(slideWidth,' > ',z.$.mask.width());
    if (slideWidth > z.$.mask.width()) {
      z.$.slide1.css({
        left: slideWidth+'px'
        ,display: ''
      });
      z.$.arrows.css('display','');
    }
    z.$.slide0.css('visibility','');
  }
  ,functionalize: function(){
    var z = this;

    z.$.arrows.bind('click',function(){
      z.slide(+$(this).attr('xdata-dir'));
    });
  }
  ,slide: function(dir){
    var z = this
    ,numVisible = Math.floor(z.$.mask.width()/z.itemWidth)
    ,moveX
    ;

    //z.$.slides.stop();
    z.$.slides.each(function(i,v){
      var jSlide = $(this)
      ,targetX = -1*jSlide.position().left + z.$.mask.width()
      ,indexOfTarget = Math.floor(targetX/z.itemWidth);
      if (indexOfTarget > 0)
        moveX = indexOfTarget*z.itemWidth + jSlide.position().left;
    });
    z.log('moveX',moveX);
  }
});


ace.init();
