/**
 @name GBanner
 @description คลาสสำหรับการแสดงรูปภาพแบบไสลด์โชว์
 @author http://www.goragod.com (goragod wiriya)
 @version 08-10-60

 @param string className คลาสของ GBanner ค่าเริ่มต้นคือ gbanner
 @param string buttonContainerClass ค่าเริ่มต้นคือbutton_container_gbanner
 @param string buttonClass ค่าเริ่มต้นคือ button_gbanner
 @param ing slideTime เวลาในการเปลี่ยนไสลด์อัตโนมัติ (msec) ค่าเริ่มต้นคือ 10000,
 @param boolean showButton แสดงปุ่มกดเลือกรูปภาพหรือไม่ ค่าเริ่มต้นคือ true,
 @param boolean showNumber แสดงตัวเลขในปุ่มกดเลือกรูปภาพหรือไม่ ค่าเริ่มต้นคือ false,
 @param boolean loop true (ค่าเริ่มต้น) วนแสดงรูปไปเรื่อยๆ
 */
var GBanner = GClass.create();
GBanner.prototype = {
  initialize: function(div, options) {
    this.options = {
      className: "gbanner",
      buttonContainerClass: "button_container_gbanner",
      buttonClass: "button_gbanner",
      slideTime: 10000,
      showButton: true,
      showNumber: false,
      loop: true
    };
    for (var property in options) {
      this.options[property] = options[property];
    }
    this.container = $G(div);
    this.container.addClass(this.options.className);
    this.container.style.overflow = "hidden";
    var tmp = this;
    this.next = this.container.create("span");
    this.next.className = "hidden";
    this.next.title = trans("Next");
    callClick(this.next, function() {
      window.clearTimeout(tmp.SlideTime);
      tmp._nextSlide();
    });
    this.prev = this.container.create("span");
    this.prev.className = "hidden";
    this.prev.title = trans("Prev");
    callClick(this.prev, function() {
      window.clearTimeout(tmp.SlideTime);
      tmp._prevSlide();
    });
    this.buttons = this.container.create("p");
    this.buttons.className = "hidden";
    this.buttons.style.zIndex = 2;
    this.button = $G(this.buttons.create("p"));
    this.button.className = this.options.buttonClass;
    this.datas = new Array();
    forEach(this.container.getElementsByTagName("figure"), function() {
      tmp._initItem(this);
    });
    this.currentId = -1;
  },
  add: function(picture, detail, url) {
    var figure = document.createElement("figure");
    this.container.appendChild(figure);
    var img = document.createElement("img");
    img.src = picture;
    img.className = "nozoom";
    figure.appendChild(img);
    var figcaption = document.createElement("figcaption");
    figure.appendChild(figcaption);
    var a = document.createElement("a");
    a.href = url;
    a.target = "_blank";
    figcaption.appendChild(a);
    if (detail && detail != "") {
      var span = document.createElement("span");
      span.innerHTML = detail;
      a.appendChild(span);
    }
    this._initItem(figure);
    return this;
  },
  JSONData: function(data) {
    try {
      var datas = eval("(" + data + ")");
      for (var i = 0; i < datas.length; i++) {
        this.add(datas[i].picture, datas[i].detail, datas[i].url);
      }
    } catch (e) {}
    return this;
  },
  _initItem: function(obj) {
    var i = this.datas.length;
    this.datas.push($G(obj));
    obj.style.display = i == 0 ? "block" : "none";
    var a = $G(this.button.create("a"));
    a.rel = i;
    var span = a.create("span");
    span.className = "preview";
    span.style.backgroundImage = "url(" + obj.firstChild.src + ")";
    if (this.options.showNumber) {
      a.appendChild(document.createTextNode(i + 1));
    }
    this.buttons.className =
      this.options.showButton && i > 0
        ? this.options.buttonContainerClass
        : "hidden";
    var tmp = this;
    callClick(a, function() {
      window.clearTimeout(tmp.SlideTime);
      tmp._show(this.rel);
    });
  },
  _prevSlide: function() {
    if (this.datas.length > 0) {
      var next = this.currentId - 1;
      if (next < 0 && this.options.loop) {
        next = this.datas.length - 1;
      }
      this._playIng(next);
    }
  },
  _nextSlide: function() {
    if (this.datas.length > 0) {
      var next = this.currentId + 1;
      if (next >= this.datas.length && this.options.loop) {
        next = 0;
      }
      this._playIng(next);
    }
  },
  _playIng: function(id) {
    if ($E(this.container.id)) {
      this._show(id);
      if (this.datas.length > 1) {
        var temp = this;
        this.SlideTime = window.setTimeout(function() {
          temp.playSlideShow.call(temp);
        }, this.options.slideTime);
      }
    }
  },
  playSlideShow: function() {
    this._nextSlide();
    return this;
  },
  _show: function(id) {
    if (this.datas[id]) {
      var temp = this;
      forEach(this.datas, function(item, index) {
        if (id == index) {
          item.style.display = "block";
          item.style.zIndex = 1;
          item.getElementsByTagName("figcaption").className = "show";
        } else if (temp.currentId == index) {
          item.style.display = "list-item";
          item.style.zIndex = 0;
          item.getElementsByTagName("figcaption").className = "";
        } else {
          item.style.display = "none";
          item.style.zIndex = 0;
          item.getElementsByTagName("figcaption").className = "";
        }
      });
      this.datas[id].addClass("fadein");
      temp._setButton(id);
      temp.currentId = id;
      window.setTimeout(function() {
        temp.datas[id].removeClass("fadein");
      }, 1000);
    }
  },
  _setButton: function(id) {
    forEach(this.button.getElementsByTagName("a"), function() {
      this.className = this.rel == id ? "current" : "";
    });
    this.prev.className = id == 0 ? "hidden" : "btnnav prev";
    this.next.className =
      id == this.datas.length - 1 ? "hidden" : "btnnav next";
  }
};
