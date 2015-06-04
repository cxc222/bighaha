(function () {
// 是否点击了发送按钮
    var isSubmit = 0;

    M.addModelFns({
        cat_form: {
            submit: function () {
                isSubmit = 1;
                var oCollection = this.elements;
                var nL = oCollection.length;
                var bValid = true;
                var dFirstError;

                for (var i = 0; i < nL; i++) {
                    var dInput = oCollection[i];
                    var sName = dInput.name;
                    // 如果没有事件节点，则直接略过检查
                    if (!sName || !dInput.getAttribute("event-node")) {
                        continue;
                    }
                    ("function" === typeof(dInput.onblur)) && dInput.onblur();
                    if (!dInput.bIsValid) {
                        bValid = false;
                        if (dInput.type != 'hidden') {
                            dFirstError = dFirstError || dInput;
                        }
                    }
                }

                dFirstError && dFirstError.focus();
                setTimeout(function () {
                    isSubmit = 0;
                }, 1500);

                return bValid;
            }}
    });


    M.addEventFns({

        input_select: {
            focus: function () {
                this.className = 'form-control';
                return false;
            },
            blur: function () {
                // 设置文本框的最大与最小输入限制
                var oArgs = M.getEventArgs(this);
                var need = oArgs.need ? parseInt(oArgs.need) : 0;

                // 最大和最小长度均小于或等于0，则不进行长度验证
                if (need == 0) {
                    return false;
                }

                var dTips = (this.parentModel.childEvents[this.getAttribute("name") + "_tips"] || [])[0];
                if ($(this).val() == -1) {
                    dTips && (dTips.style.display = "none");
                    tips.error(this, oArgs.error);
                    this.bIsValid = false;
                } else {
                    tips.success(this);
                    dTips && (dTips.style.display = "");
                    this.bIsValid = true;
                }
                return false;
            },
            load: function () {
                this.className = 'form-control';
            }
        },
        uploadinput: {
            focus: function () {
                return false;
            },
            blur: function () {
                var oArgs = M.getEventArgs(this);
                var need = oArgs.need ? parseInt(oArgs.need) : 0;
                // 最大和最小长度均小于或等于0，则不进行长度验证
                if (need != 1) {
                    this.bIsValid = true;
                    return false;
                }
                var through = 1;
                //  var dTips = (this.parentModel.childEvents[this.getAttribute("name") + "_tips"] || [])[0];
                //  if ($(this).attr('up-type') == 'pic') {
                if (parseInt($(this).val()) == 0 || $(this).val() == '') {
                    through = 0;
                }

                //  }

                if (!through) {
                    tips.error(this, oArgs.error);
                    this.bIsValid = false;
                } else {
                    tips.success(this);
                    this.bIsValid = true;
                }
                return false;
            },
            load: function () {
                this.className = 's-select';
            },
            change: function () {
                var oArgs = M.getEventArgs(this);
                var need = oArgs.need ? parseInt(oArgs.need) : 0;
                // 最大和最小长度均小于或等于0，则不进行长度验证
                if (need != 1) {
                    this.bIsValid = true;
                    return false;
                }
                var through = 1;
                //  var dTips = (this.parentModel.childEvents[this.getAttribute("name") + "_tips"] || [])[0];
                //  if ($(this).attr('up-type') == 'pic') {
                if (parseInt($(this).val()) == 0 || $(this).val() == '') {
                    through = 0;
                }

                //  }

                if (!through) {
                    tips.error(this, oArgs.error);
                    this.bIsValid = false;
                } else {
                    tips.success(this);
                    this.bIsValid = true;
                }
                return false;
            }

        },
        // 文本框输入文本验证
        input_text: {
            focus: function () {

                return false;
            },
            blur: function () {
                this.className = 'form-control';
                // 设置文本框的最大与最小输入限制
                var oArgs = M.getEventArgs(this);
                var min = oArgs.min ? parseInt(oArgs.min) : 0;
                var max = oArgs.max ? parseInt(oArgs.max) : 0;
                // 最大和最小长度均小于或等于0，则不进行长度验证
                if (min <= 0 && max <= 0) {
                    return false;
                }

                var dTips = (this.parentModel.childEvents[this.getAttribute("name") + "_tips"] || [])[0];
                var sValue = this.value;
                sValue = sValue.replace(/(^\s*)|(\s*$)/g, "");
                var nL = sValue.replace(/[^\x00-\xff]/ig, 'xx').length / 2;

                if (nL <= min - 1 || ( max && nL > max)) {
                    dTips && (dTips.style.display = "none");
                    tips.error(this, oArgs.error);
                    this.bIsValid = false;
                } else {
                    tips.success(this);
                    dTips && (dTips.style.display = "");
                    this.bIsValid = true;
                }
                return false;
            },
            load: function () {
                this.className = 'form-control';
            }
        },
        // 文本框输入纯数字文本验证
        input_nums: {
            focus: function () {

                return false;
            },
            blur: function () {
                this.className = 'form-control';
                // 设置文本框的最大与最小输入限制
                var oArgs = M.getEventArgs(this);
                var min = oArgs.min ? parseInt(oArgs.min) : 0;
                var max = oArgs.max ? parseInt(oArgs.max) : 0;
                // 最大和最小长度均小于或等于0，则不进行长度验证
                if (min <= 0 && max <= 0) {
                    return false;
                }

                var dTips = (this.parentModel.childEvents[this.getAttribute("name") + "_tips"] || [])[0];
                var sValue = this.value;

                // 纯数字验证
                var re = /^[0-9]*$/;
                if (!re.test(sValue)) {
                    dTips && (dTips.style.display = "none");
                    tips.error(this, L('PUBLIC_TYPE_ISNOT'));		// 格式不正确
                    this.bIsValid = false;
                    return false;
                }

                sValue = sValue.replace(/(^\s*)|(\s*$)/g, "");
                var nL = sValue.replace(/[^\x00-\xff]/ig, 'xx').length / 2;

                if (nL <= min - 1 || (max && nL > max)) {
                    dTips && (dTips.style.display = "none");
                    tips.error(this, oArgs.error);
                    this.bIsValid = false;
                } else {
                    tips.success(this);
                    dTips && (dTips.style.display = "");
                    this.bIsValid = true;
                }

                return false;
            },
            load: function () {
                this.className = 'form-control';
            }
        },
        // 文本域验证
        textarea: {
            blur: function () {
                // 设置文本框的最大与最小输入限制
                var oArgs = M.getEventArgs(this);
                var min = oArgs.min ? parseInt(oArgs.min) : 0;
                var max = oArgs.max ? parseInt(oArgs.max) : 0;
                // 最大和最小长度均小于或等于0，则不进行长度验证
                if (min <= 0 && max <= 0) {
                    return false;
                }

                if ($.trim(this.value)) {
                    tips.success(this);
                    this.bIsValid = true;
                } else {
                    if ("undefined" != typeof(oArgs.error )) {
                        tips.error(this, oArgs.error);
                        this.bIsValid = false;
                    }
                }
            }
        },

        // 地区信息验证
        input_area: {
            blur: function () {
                // 获取数据
                var sValue = $.trim(this.value);
                var sValueArr = sValue.split(",");
                // 验证数据正确性
                if (sValue == "" || sValueArr[0] == 0) {
                    tips.error(this, "请选择地区");
                    this.bIsValid = false;
                    this.value = '0,0,0';
                } else if (sValueArr[1] == 0 || sValueArr[2] == 0) {
                    tips.error(this, "请选择完整地区信息");
                    this.bIsValid = false;
                } else {
                    tips.success(this);
                    this.bIsValid = true;
                }
            },
            load: function () {
                // 获取参数信息
                var _this = this;
                // 验证数据正确性
                setInterval(function () {
                    // 获取数据
                    var sValue = $.trim(_this.value);
                    var sValueArr = sValue.split(",");
                    // 验证数据正确性
                    if (sValue == "" || sValueArr[0] == 0) {
                        tips.error(_this, "请选择地区");
                        _this.bIsValid = false;
                    } else if (sValueArr[1] == 0 || sValueArr[2] == 0) {
                        tips.error(_this, "请选择完整地区信息");
                        _this.bIsValid = false;
                    } else {
                        tips.success(_this);
                        _this.bIsValid = true;
                    }
                }, 200);
            }
        },
        // 时间格式验证
        input_date: {
            focus: function () {


                var dDate = this;
                var oArgs = M.getEventArgs(this);

                /*M.getJS(THEME_URL + '/js/rcalendar.js', function () {
                 rcalendar(dDate, oArgs.mode);
                 });*/
            },
            blur: function () {
                this.className = 'form-control';

                var dTips = (this.parentModel.childEvents[this.getAttribute("name") + "_tips"] || [])[0];
                var oArgs = M.getEventArgs(this);
                if (oArgs.min == 0) {
                    return true;
                }
                var _this = this;
                setTimeout(function () {
                    sValue = _this.value;
                    if (!sValue) {
                        dTips && (dTips.style.display = "none");
                        tips.error(_this, oArgs.error);
                        this.bIsValid = false;
                    } else {
                        tips.success(_this);
                        dTips && (dTips.style.display = "");
                        _this.bIsValid = true;
                    }
                }, 250);
            },
            load: function () {
                this.className = 'form-control';
            }
        },
        // 邮箱验证
        email: {
            focus: function () {

                var x = $(this).offset();
                $(this.dTips).css({'position': 'absolute', 'left': x.left + 'px', 'top': x.top + $(this).height() + 12 + 'px', 'width': $(this).width() + 10 + 'px'});
            },
            blur: function () {
                this.className = 'form-control';

                var dEmail = this;
                var sUrl = dEmail.getAttribute("checkurl");
                var sValue = dEmail.value;

                if (!sUrl || (this.dSuggest && this.dSuggest.isEnter)) {
                    return false;
                }

                $.post(sUrl, {email: sValue}, function (oTxt) {
                    var oArgs = M.getEventArgs(dEmail);
                    if (oTxt.status) {
                        "false" == oArgs.success ? tips.clear(dEmail) : tips.success(dEmail);
                        dEmail.bIsValid = true;
                    } else {
                        "false" == oArgs.error ? tips.clear(dEmail) : tips.error(dEmail, oTxt.info);
                        dEmail.bIsValid = false;
                    }
                    return true;
                }, 'json');
                $(this.dTips).hide();
            },
            load: function () {
                this.className = 'form-control';

                var dEmail = this;
                var oArgs = M.getEventArgs(this);

                if (!oArgs.suffix) {
                    return false;
                }

                var aSuffix = oArgs.suffix.split(",");
                var dFrag = document.createDocumentFragment();
                var dTips = document.createElement("div");
                var dUl = document.createElement("ul");

                this.dTips = $(dTips);
                $('body').append(this.dTips);

                dTips.className = "mod-at-wrap";
                dDiv = dTips.appendChild(dTips.cloneNode(false));
                dDiv.className = "mod-at";
                dDiv = dDiv.appendChild(dTips.cloneNode(false));
                dDiv.className = "mod-at-list";
                dUl = dDiv.appendChild(dUl);
                dUl.className = "at-user-list";
                dTips.style.display = "none";
                dEmail.parentNode.appendChild(dFrag);

                M.addListener(dTips, {
                    mouseenter: function () {
                        this.isEnter = 1;
                    },
                    mouseleave: function () {
                        this.isEnter = 0;
                    }
                });

                // 附加到Input DOM 上
                dEmail.dSuggest = dTips;

                setInterval(function () {
                    var sValue = dEmail.value;
                    var sTips = dEmail.dSuggest;
                    if (dEmail.sCacheValue === sValue) {
                        return false;
                    } else {
                        // 缓存值
                        dEmail.sCacheValue = sValue;
                    }
                    // 空值判断
                    if (!sValue) {
                        dTips.style.display = "none";
                        return;
                    }
                    var aValue = sValue.split("@");
                    var dFrag = document.createDocumentFragment();
                    var l = aSuffix.length;
                    var sSuffix;

                    sInputSuffix = ["@", aValue[1]].join(""); // 用户输入的邮箱的后缀

                    for (var i = 0; i < l; i++) {
                        sSuffix = aSuffix[i];
                        if (aValue[1] && ( "" != aValue[1] ) && (sSuffix.indexOf(aValue[1]) !== 1 ) || (sSuffix === sInputSuffix)) {
                            continue;
                        }
                        var dLi = dLi ? dLi.cloneNode(false) : document.createElement("li");
                        var dA = dA ? dA.cloneNode(false) : document.createElement("a");
                        var dSpan = dSpan ? dSpan.cloneNode(false) : document.createElement("span");
                        var dText = dText ? dText.cloneNode(false) : document.createTextNode("");

                        dText.nodeValue = [aValue[0], sSuffix].join("");

                        dSpan.appendChild(dText);

                        dA.appendChild(dSpan);

                        dLi.appendChild(dA);

                        dLi.onclick = (function (dInput, sValue, sSuffix) {
                            return function (e) {
                                dInput.value = [ sValue, sSuffix ].join("");
                                // 选择完毕，状态为离开选择下拉条
                                dTips.isEnter = 0;
                                // 自动验证
                                dInput.onblur();
                                return false;
                            };
                        })(dEmail, aValue[0], sSuffix);

                        dFrag.appendChild(dLi);
                    }
                    if (dLi) {
                        dUl.innerHTML = "";
                        dUl.appendChild(dFrag);
                        dTips.style.display = "";
                        $(dUl).find('li').hover(function () {
                            $(this).addClass('hover');
                        }, function () {
                            $(this).removeClass('hover');
                        });

                    } else {
                        dTips.style.display = "none";
                    }
                }, 200);
            }
        },
        // 密码验证
        password: {
            focus: function () {

            },
            blur: function () {
                this.className = 'form-control';
                var dWeight = this.parentModel.childModels["password_weight"][0];
                var sValue = this.value + "";
                var nL = sValue.length;
                var min = 6
                var max = 15;
                if (nL < min) {
                    dWeight.style.display = "none";
                    tips.error(this, L('PUBLIC_PASSWORD_TIPES_MIN', {'sum': min}));
                    this.bIsValid = false;
                } else if (nL > max) {
                    dWeight.style.display = "none";
                    tips.error(this, L('PUBLIC_PASSWORD_TIPES_MAX', {'sum': max}));
                    this.bIsValid = false;
                } else {
                    tips.clear(this);
                    dWeight.style.display = "";
                    this.bIsValid = true;
                    this.parentModel.childEvents["repassword"][0].onblur();
                }
            },
            keyup: function () {
                this.value = this.value.replace(/^\s+|\s+$/g, "");
            },
            load: function () {
                this.value = '';
                this.className = 'form-control';

                var dPwd = this,
                    dWeight = this.parentModel.childModels["password_weight"][0],
                    aLevel = [ "psw-state-empty", "psw-state-poor", "psw-state-normal", "psw-state-strong" ];

                setInterval(function () {
                    var sValue = dPwd.value;
                    // 缓存值
                    if (dPwd.sCacheValue === sValue) {
                        return;
                    } else {
                        dPwd.sCacheValue = sValue;
                    }
                    // 空值判断
                    if (!sValue) {
                        dWeight.className = aLevel[0];
                        dWeight.setAttribute('className', aLevel[0]);
                        return;
                    }
                    var nL = sValue.length;

                    if (nL < 6) {
                        dWeight.className = aLevel[0];
                        dWeight.setAttribute('className', aLevel[0]);
                        return;
                    }

                    var nLFactor = Math.floor(nL / 10) ? 1 : 0;
                    var nMixFactor = 0;

                    sValue.match(/[a-zA-Z]+/) && nMixFactor++;
                    sValue.match(/[0-9]+/) && nMixFactor++;
                    sValue.match(/[^a-zA-Z0-9]+/) && nMixFactor++;
                    nMixFactor > 1 && nMixFactor--;

                    dWeight.className = aLevel[nLFactor + nMixFactor];
                    dWeight.setAttribute('className', aLevel[nLFactor + nMixFactor]);

                }, 200);
            }
        },
        repassword: {
            focus: function () {

            },
            keyup: function () {
                this.value = this.value.replace(/^\s+|\s+$/g, "");
            },
            blur: function () {
                this.className = 'form-control';

                var sPwd = this.parentModel.childEvents["password"][0].value,
                    sRePwd = this.value;

                if (!sRePwd) {
                    tips.error(this, L('PUBLIC_PLEASE_PASSWORD_ON'));
                    this.bIsValid = false;
                } else if (sPwd !== sRePwd) {
                    tips.error(this, L('PUBLIC_PASSWORD_ISDUBLE_NOT'));
                    this.bIsValid = false;
                } else {
                    tips.success(this);
                    this.bIsValid = true;
                }
            },
            load: function () {
                this.className = 'form-control';
            }
        },
        // 昵称验证
        uname: {
            focus: function () {

                return false;
            },
            blur: function () {
                this.className = 'form-control';

                var dUname = this;
                var sUrl = dUname.getAttribute('checkurl');
                var sValue = dUname.value;
                var oArgs = M.getEventArgs(dUname);
                var oValue = oArgs.old_name;

                if (!sUrl || (this.dSuggest && this.dSuggest.isEnter)) return;

                $.post(sUrl, {uname: sValue, old_name: oValue}, function (oTxt) {
                    if (oTxt.status) {
                        'false' == oArgs.success ? tips.clear(dUname) : tips.success(dUname);
                        dUname.bIsValid = true;
                    } else {
                        'false' == oArgs.error ? tips.clear(dUname) : tips.error(dUname, oTxt.info);
                        dUname.bIsValid = false;
                    }
                    return true;
                }, 'json');
                $(this.dTips).hide();
            },
            load: function () {
                this.className = 'form-control';
            }
        },
        /* input_radio: {
         click: function () {
         this.onblur();
         },
         blur: function () {
         var sName = this.name,
         oRadio = this.parentModel.elements["sex"],
         oArgs = M.getEventArgs(oRadio[0]),
         dRadio, nL = oRadio.length, bIsValid = false,
         dLastRadio = oRadio[nL - 1];

         for (var i = 0; i < nL; i++) {
         dRadio = oRadio[i];
         if (dRadio.checked) {
         bIsValid = true;
         break;
         }
         }

         if (bIsValid) {
         tips.clear(dLastRadio.parentNode);
         } else {
         tips.error(dLastRadio.parentNode, oArgs.error);
         }

         for (var i = 0; i < nL; i++) {
         oRadio[i].bIsValid = bIsValid;
         }
         }
         },*/
        radio: {
            click: function () {
                this.onblur();
            },
            blur: function () {
                var sName = this.name,
                    oRadio = this.parentModel.elements["sex"],
                    oArgs = M.getEventArgs(oRadio[0]),
                    dRadio, nL = oRadio.length, bIsValid = false,
                    dLastRadio = oRadio[nL - 1];

                for (var i = 0; i < nL; i++) {
                    dRadio = oRadio[i];
                    if (dRadio.checked) {
                        bIsValid = true;
                        break;
                    }
                }

                if (bIsValid) {
                    tips.clear(dLastRadio.parentNode);
                } else {
                    tips.error(dLastRadio.parentNode, oArgs.error);
                }

                for (var i = 0; i < nL; i++) {
                    oRadio[i].bIsValid = bIsValid;
                }
            }
        },
        checkbox: {
            click: function () {
                this.onblur();
            },
            blur: function () {
                var oArgs = M.getEventArgs(this);

                var chkBox = this;
                var isValid = false;
                if (parseInt(oArgs.need) == 1) {
                    var allChkBox = $(this).parents('div').eq(0).find('input[type=checkbox]');
                    allChkBox.each(function (index, element) {

                        if ($(element).prop('checked') == true) {
                            for (var i = 0; i < allChkBox.length; i++) {
                                allChkBox[i].bIsValid = true;
                                console.log(allChkBox.eq(i).bIsValid)
                            }
                            isValid = true;
                            return false;
                        }

                    });
                    if (isValid != true) {
                        chkBox.bIsValid = false;
                        tips.error(this, oArgs.error);
                    } else {
                        tips.success(this);
                    }
                    // chkBox.bIsValid=false;
                    // return true;

                } else {
                    chkBox.bIsValid = true;
                    tips.success(this);
                }
                /* if (this.checked) {
                 tips.clear(this.parentNode);
                 this.bIsValid = true;
                 } else {
                 tips.error(this.parentNode, oArgs.error);
                 this.bIsValid = false;
                 }*/
            }
        },
        submit_btn: {
            click: function () {
                var args = M.getEventArgs(this);
                if (args.info && !confirm(args.info)) {
                    return false;
                }
                try {
                    (function (node) {
                        var parent = node.parentNode;
                        // 判断node 类型，防止意外循环
                        if ("FORM" === parent.nodeName) {
                            if ("false" === args.ajax) {
                                ( ( "function" !== typeof parent.onsubmit ) || ( false !== parent.onsubmit() ) ) && parent.submit();
                            } else {
                                ajaxSubmit(parent);
                            }
                        } else if (1 === parent.nodeType) {
                            arguments.callee(parent);
                        }
                    })(this);
                } catch (e) {
                    return true;
                }
                return false;
            }
        },
        sendBtn: {
            click: function () {
                var parent = this.parentModel;
                return false;
            }
        }
    });
    /**
     * 提示语Js对象
     */
    var tips = {
        /**
         * 调用错误接口
         * @param object D DOM对象
         * @param string txt 显示内容
         * @return void
         */
        error: function (D, txt) {
            this._D = D;
            if (this._isFirstValidate()) {
                this.initFeedBack()
            }
            this._showTip(txt);
            this._changeFeedBackType('error');

        },
        /**
         * 调用成功接口
         * @param object D DOM对象
         * @return void
         */
        success: function (D) {
            this._D = D;
            if (this._isFirstValidate()) {
                this.initFeedBack()
            }
            this._changeFeedBackType('success');

            this._hideTip();

        },
        _showTip:function(txt){
            if ($(this._D).attr('type') == 'checkbox') {
                var $first=$(this._D).parents('div').eq(0).find('input[type=checkbox]:first');
                $first.popover({content: txt, placement: 'left'}).popover('show')
            }else{
                $(this._D).popover({content: txt, placement: 'top'}).popover('show')
            }
        },
        _hideTip:function(){
            if ($(this._D).attr('type') == 'checkbox') {
                var $first=$(this._D).parents('div').eq(0).find('input[type=checkbox]:first');
                $first.popover('hide')
            }else{
                $(this._D).popover('hide')
            }
        },
        /**
         * 清除提示接口
         * @param object D DOM对象
         * @return void
         */
        clear: function (D) {
            this.init(D);
            D.dFeedBack.style.display = "none";
        },


        /**
         * 初始化状态框
         */
        initFeedBack: function () {

            // 创建DOM结构
            var dFrag = document.createDocumentFragment();
            var dSpan = document.createElement("span");
            // 组装HTML结构 - DIV
            this._D.dFeedBack = dFrag.appendChild(dSpan);
            dSpan.className = "glyphicon form-control-feedback";
            //TODO 调整错误提示
            // 插入HTML
            if ($(this._D).attr('type') != 'checkbox') {
                $(this._D).after(dSpan);
            } else {
                $(this._D).parents('div').eq(0).find('input[type=checkbox]:last').parent().after(dSpan);
            }

        },

        /**
         * 改变提示类型
         * @param type
         */
        _changeFeedBackType: function (type) {
            if (type == 'error') {
                this._setFeedBackElementIcon('remove');
                this._setFeedBackElementClass(type);
            }
            if (type == 'success') {
                this._setFeedBackElementIcon('ok');
                this._setFeedBackElementClass(type);
            }
            if (type == 'warning') {
                this._setFeedBackElementIcon('warning');
                this._setFeedBackElementClass(type);
            }
        },
        _setFeedBackElementClass: function (type) {
            $(this._D).parents('.form-group').eq(0).attr('class', 'form-group has-' + type + ' has-feedback');
        },
        _setFeedBackElementIcon: function (icon) {
            if (typeof(this._D.dFeedBack) == 'undefined') {
                this._D.dFeedBack = $(this._D.parentNode).parent().find('span.form-control-feedback')[0];
            }
            this._D.dFeedBack.className = 'glyphicon glyphicon-' + icon + ' form-control-feedback';
        },

        _isFirstValidate: function () {
            var dParent = this._D.parentNode;
            if ($(this._D).attr('type') != 'checkbox') {
                return $(dParent).find('span.form-control-feedback').length == 0;
            } else {
                return $(dParent).parent().find('span.form-control-feedback').length == 0;
            }
        },

        /**
         * 初始化错误对象
         * @param object D DOM对象
         * @return void
         * @private
         */
        _initError: function (D) {

        },
        /**
         * 初始化成功对象
         * @param object D DOM对象
         * @return void
         * @private
         */
        _initSuccess: function (D) {
            if (!D.dSuccess) {
                // 创建DOM结构
                var dFrag = document.createDocumentFragment();
                var dSpan = document.createElement("span");
                // 组装HTML结构 - SPAN
                D.dSuccess = dFrag.appendChild(dSpan);
                dSpan.className = "ico-ok";
                dSpan.style.display = "none";
                // 插入HTML
                var dParent = D.parentNode;
                var dNext = D.nextSibling;
                if (dNext) {
                    // dParent.nextSibling.innerHTML='';
                    dParent.nextSibling.appendChild(dFrag);
                    // dParent.insertBefore(dFrag, dNext);
                } else {
                    dParent.appendChild(dFrag);
                }
            }
        }
    };
// 定义Window属性
    window.tips = tips;
})();
