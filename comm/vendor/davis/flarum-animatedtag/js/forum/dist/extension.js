System.register('davis/animatedtag/main', ['flarum/extend', 'flarum/components/DiscussionHero', 'flarum/components/DiscussionListItem'], function (_export) {
    'use strict';

    var extend, DiscussionHero, DiscussionListItem;
    return {
        setters: [function (_flarumExtend) {
            extend = _flarumExtend.extend;
        }, function (_flarumComponentsDiscussionHero) {
            DiscussionHero = _flarumComponentsDiscussionHero['default'];
        }, function (_flarumComponentsDiscussionListItem) {
            DiscussionListItem = _flarumComponentsDiscussionListItem['default'];
        }],
        execute: function () {

            app.initializers.add('davis-animatedtag', function () {
                extend(DiscussionHero.prototype, 'config', function () {
                    if (document.getElementById('tag-canvas')) {} else if (document.getElementsByClassName("Hero")[0]) {
                        if (document.getElementsByClassName("WelcomeHero")[0]) {} else {
                            renderani(app.forum.attribute('animationtype'));
                        }
                    }
                });
                extend(DiscussionListItem.prototype, 'config', function () {
                    if (document.getElementById('tag-canvas')) {} else if (document.getElementsByClassName("Hero")[0]) {
                        if (document.getElementsByClassName("WelcomeHero")[0]) {} else {
                            renderani(app.forum.attribute('animationtype'));
                        }
                    }
                });

                function renderani(type) {
                    //Define Varibles
                    var width,
                        largeHeader,
                        canvas,
                        ctx,
                        triangles,
                        circles,
                        height,
                        target,
                        topbar,
                        heroitems,
                        animateHeader = true;
                    var rerun = false;
                    var tpcolor = {};
                    var colors = [];
                    var i = 0;

                    var cltp = document.getElementsByClassName("Hero")[0].style['background-color'];
                    cltp = cltp.substring(4, cltp.length - 1).replace(/ /g, '').split(',');
                    cltp[0] = Number(cltp[0]);
                    cltp[1] = Number(cltp[1]);
                    cltp[2] = Number(cltp[2]);
                    //Convert Hero Background to HEX
                    var cl = $ui.color.rgb2hex(cltp);
                    //Get Tetradic Colors from background
                    var tempclr = $ui.color.tetradic(cl);
                    //Remove Background color from group
                    tempclr.splice(0, 1);
                    //Put colors into array
                    while (i < 3) {
                        //Change color to RGB
                        tpcolor[i] = $ui.color.hex2rgb(tempclr[i]);
                        //Change RGB Array into String
                        colors[i] = tpcolor[i][0] + "," + tpcolor[i][1] + "," + tpcolor[i][2];
                        //Go to next color
                        i++;
                    }
                    //Run Animation
                    initHeader();
                    addListeners();

                    function initHeader() {
                        width = window.innerWidth;
                        if (768 <= window.innerWidth) {
                            topbar = 52;
                            if (document.getElementsByClassName("DiscussionHero-title")[0]) {
                                height = 141;
                            } else {
                                if (document.getElementsByClassName("Hero-subtitle")[0]) {
                                    if (document.getElementsByClassName("Hero-subtitle")[0].innerHTML) {
                                        height = 113 + document.getElementsByClassName("Hero-subtitle")[0].clientHeight; //Take out 20   
                                    } else {
                                            height = 111;
                                        }
                                }
                            }
                        } else if (0 < window.innerWidth < 768) {
                            topbar = 46;
                            if (document.getElementsByClassName("DiscussionHero-title")[0]) {
                                height = 102;
                            } else {
                                if (document.getElementsByClassName("Hero-subtitle")[0]) {
                                    if (document.getElementsByClassName("Hero-subtitle")[0].innerHTML) {
                                        height = 71 + document.getElementsByClassName("Hero-subtitle")[0].clientHeight; //Take out 20  
                                    } else {
                                            height = 72;
                                        }
                                }
                            }
                        } else {
                            resize();
                        }
                        target = { x: 0, y: height };

                        largeHeader = document.getElementsByClassName("Hero")[0];
                        heroitems = document.getElementsByClassName("container")[1];
                        largeHeader.style.height = height + 'px';
                        heroitems.style.top = topbar + "px";
                        heroitems.style.position = "absolute";
                        heroitems.style.width = "100%";

                        var canvastemp = document.createElement('canvas');
                        canvastemp.setAttribute("id", "tag-canvas");
                        if (document.getElementById('tag-canvas')) {} else {
                            largeHeader.insertBefore(canvastemp, largeHeader.firstChild);
                        }

                        canvas = document.getElementById('tag-canvas');
                        canvas.width = width;
                        canvas.height = height;
                        ctx = canvas.getContext('2d');

                        /*
                        var particles = [];
                        var patriclesNum = 500;
                        var w = 1920;
                        var h = 500;
                        var colors = ['#f35d4f','#f36849','#c0d988','#6ddaf1','#f1e85b'];
                                  canvas.width = 500;
                        canvas.height = 500;
                        canvas.style.left = (window.innerWidth - 500)/2+'px';
                                 if(window.innerHeight>500)
                        canvas.style.top = (window.innerHeight - 500)/2+'px';
                                 */
                        // create particles
                        switch (type) {
                            case "0":
                                triangles = [];
                                for (var x = 0; x < 480; x++) {
                                    addTriangle(x * 10);
                                }
                                break;
                            case "1":
                                circles = [];
                                for (var x = 0; x < width * 0.5; x++) {
                                    var c = new Circle();
                                    circles.push(c);
                                }
                                break;
                            case "2":
                                circles = [];
                                for (var x = 0; x < width * 0.5; x++) {
                                    var c = new Circle();
                                    circles.push(c);
                                }
                                break;
                            case "3":
                                // robot();
                                break;
                        }
                        animate();
                        resize();
                    }

                    function addTriangle(delay) {
                        setTimeout(function () {
                            var t = new Triangle();
                            triangles.push(t);
                            tweenTriangle(t);
                        }, delay);
                    }

                    function tweenTriangle(tri) {
                        var t = Math.random() * (2 * Math.PI);
                        var x = (200 + Math.random() * 100) * Math.cos(t) + width * 0.5;
                        var y = (200 + Math.random() * 100) * Math.sin(t) + height * 0.5 - 20;
                        var time = 4 + 3 * Math.random();

                        TweenLite.to(tri.pos, time, { x: x,
                            y: y, ease: Circ.easeOut,
                            onComplete: function onComplete() {
                                tri.init();
                                tweenTriangle(tri);
                            } });
                    }

                    // Event handling
                    function addListeners() {
                        window.addEventListener('scroll', scrollCheck);
                        window.addEventListener('resize', resize);
                    }

                    function scrollCheck() {
                        if (document.body.scrollTop > height) animateHeader = false;else animateHeader = true;
                    }

                    function resize() {
                        width = window.innerWidth;
                        if (768 <= window.innerWidth) {
                            topbar = 52;
                            if (document.getElementsByClassName("DiscussionHero-title")[0]) {
                                height = 141;
                            } else {
                                if (document.getElementsByClassName("Hero-subtitle")[0]) {
                                    if (document.getElementsByClassName("Hero-subtitle")[0].innerHTML) {
                                        height = 113 + document.getElementsByClassName("Hero-subtitle")[0].clientHeight; //Take out 20
                                    } else {
                                            height = 111;
                                        }
                                }
                            }
                        } else if (0 < window.innerWidth < 768) {
                            topbar = 46;
                            if (document.getElementsByClassName("DiscussionHero-title")[0]) {
                                height = 102;
                            } else {
                                if (document.getElementsByClassName("Hero-subtitle")[0]) {
                                    if (document.getElementsByClassName("Hero-subtitle")[0].innerHTML) {
                                        height = 71 + document.getElementsByClassName("Hero-subtitle")[0].clientHeight; //Take out 20
                                    } else {
                                            height = 72;
                                        }
                                }
                            }
                        } else {
                            resize();
                        }
                        heroitems.style.top = topbar + "px";
                        largeHeader.style.height = height + 'px';
                        canvas.width = width;
                        canvas.height = height;
                    }

                    function animate() {
                        switch (type) {
                            case "0":
                                if (animateHeader) {
                                    ctx.clearRect(0, 0, width, height);
                                    for (var i in triangles) {
                                        triangles[i].draw();
                                    }
                                }
                                requestAnimationFrame(animate);
                                break;
                            case "1":
                                if (animateHeader) {
                                    ctx.clearRect(0, 0, width, height);
                                    for (var i in circles) {
                                        circles[i].draw();
                                    }
                                }
                                requestAnimationFrame(animate);
                                break;
                            case "2":
                                if (animateHeader) {
                                    ctx.clearRect(0, 0, width, height);
                                    for (var i in circles) {
                                        circles[i].draw();
                                    }
                                }
                                requestAnimationFrame(animate);
                                break;
                        }
                    }
                    // Canvas manipulation
                    function Triangle() {
                        var _this = this;

                        // constructor
                        (function () {
                            _this.coords = [{}, {}, {}];
                            _this.pos = {};
                            init();
                        })();

                        function init() {
                            _this.pos.x = width * 0.5;
                            _this.pos.y = height * 0.5 - 20;
                            _this.coords[0].x = -10 + Math.random() * 40;
                            _this.coords[0].y = -10 + Math.random() * 40;
                            _this.coords[1].x = -10 + Math.random() * 40;
                            _this.coords[1].y = -10 + Math.random() * 40;
                            _this.coords[2].x = -10 + Math.random() * 40;
                            _this.coords[2].y = -10 + Math.random() * 40;
                            _this.scale = 0.1 + Math.random() * 0.3;
                            _this.color = colors[Math.floor(Math.random() * colors.length)];
                            setTimeout(function () {
                                _this.alpha = 0.8;
                            }, 10);
                        }

                        this.draw = function () {
                            if (_this.alpha >= 0.005) _this.alpha -= 0.005;else _this.alpha = 0;
                            ctx.beginPath();
                            ctx.moveTo(_this.coords[0].x + _this.pos.x, _this.coords[0].y + _this.pos.y);
                            ctx.lineTo(_this.coords[1].x + _this.pos.x, _this.coords[1].y + _this.pos.y);
                            ctx.lineTo(_this.coords[2].x + _this.pos.x, _this.coords[2].y + _this.pos.y);
                            ctx.closePath();
                            ctx.fillStyle = 'rgba(' + _this.color + ',' + _this.alpha + ')';
                            ctx.fill();
                        };

                        this.init = init;
                    }
                    // Canvas manipulation
                    function Circle() {
                        var _this = this;

                        // constructor
                        (function () {
                            _this.pos = {};
                            init();
                        })();

                        function init() {
                            _this.pos.x = Math.random() * width;
                            if (type == "1") {
                                _this.pos.y = height + Math.random() * 100;
                            } else if (type == "2") {
                                _this.pos.y = Math.random() * 100 * -1;
                            }
                            _this.alpha = 0.1 + Math.random() * 0.3;
                            _this.scale = 0.1 + Math.random() * 0.3;
                            _this.velocity = Math.random();
                        }

                        this.draw = function () {
                            if (_this.alpha <= 0) {
                                init();
                            }
                            if (type == "1") {
                                _this.pos.y -= _this.velocity;
                            } else if (type == "2") {
                                _this.pos.y += _this.velocity;
                            }
                            _this.alpha -= 0.0005;
                            ctx.beginPath();
                            ctx.arc(_this.pos.x, _this.pos.y, _this.scale * 10, 0, 2 * Math.PI, false);
                            ctx.fillStyle = 'rgba(255,255,255,' + _this.alpha + ')';
                            ctx.fill();
                        };
                    }
                    function robot() {
                        (function init() {
                            for (var i = 0; i < patriclesNum; i++) {
                                particles.push(new Factory());
                            }
                        })();

                        (function loop() {
                            draw();
                            requestAnimFrame(loop);
                        })();

                        function Factory() {
                            this.x = Math.round(Math.random() * w);
                            this.y = Math.round(Math.random() * h);
                            this.rad = Math.round(Math.random() * 1) + 1;
                            this.rgba = colors[Math.round(Math.random() * 3)];
                            this.vx = Math.round(Math.random() * 3) - 1.5;
                            this.vy = Math.round(Math.random() * 3) - 1.5;
                        }

                        function draw() {
                            ctx.clearRect(0, 0, w, h);
                            ctx.globalCompositeOperation = 'lighter';
                            for (var i = 0; i < patriclesNum; i++) {
                                var temp = particles[i];
                                var factor = 1;

                                for (var j = 0; j < patriclesNum; j++) {

                                    var temp2 = particles[j];
                                    ctx.linewidth = 0.5;

                                    if (temp.rgba == temp2.rgba && findDistance(temp, temp2) < 50) {
                                        ctx.strokeStyle = temp.rgba;
                                        ctx.beginPath();
                                        ctx.moveTo(temp.x, temp.y);
                                        ctx.lineTo(temp2.x, temp2.y);
                                        ctx.stroke();
                                        factor++;
                                    }
                                }

                                ctx.fillStyle = temp.rgba;
                                ctx.strokeStyle = temp.rgba;

                                ctx.beginPath();
                                ctx.arc(temp.x, temp.y, temp.rad * factor, 0, Math.PI * 2, true);
                                ctx.fill();
                                ctx.closePath();

                                ctx.beginPath();
                                ctx.arc(temp.x, temp.y, (temp.rad + 5) * factor, 0, Math.PI * 2, true);
                                ctx.stroke();
                                ctx.closePath();

                                temp.x += temp.vx;
                                temp.y += temp.vy;

                                if (temp.x > w) temp.x = 0;
                                if (temp.x < 0) temp.x = w;
                                if (temp.y > h) temp.y = 0;
                                if (temp.y < 0) temp.y = h;
                            }
                        }

                        function findDistance(p1, p2) {
                            return Math.sqrt(Math.pow(p2.x - p1.x, 2) + Math.pow(p2.y - p1.y, 2));
                        }

                        window.requestAnimFrame = (function () {
                            return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || function (callback) {
                                window.setTimeout(callback, 1000 / 60);
                            };
                        })();
                    }
                }
            });
        }
    };
});