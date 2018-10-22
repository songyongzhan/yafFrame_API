<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/19
 * Time: 17:19
 * Email: songyongzhan@qianbao.com
 */

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>您请求的页面出错了</title>
    <style type="text/css">
      ::selection { background-color: #E13300; color: white; }
      ::-moz-selection { background-color: #E13300; color: white; }

      body {
        background-color: #fff;
        margin: 40px;
        font: 13px/20px normal Helvetica, Arial, sans-serif;
        color: #4F5155;
      }

      nav {
        background-color: #29313f;
        height: 1.2rem;
        line-height: 1.3rem;
        overflow: hidden;
        width: 100%;
        top: 0;
        left: 0;
        z-index: 9999;
      }
      nav .leftIco {
        position: absolute;
        left: 0;
        top: 0;
        padding-left: 0.25rem;
        width: 1.4rem ;
        color: #ff8800;
      }
      nav .rightIco {
        position: absolute;
        right: 0;
        top: 0;
        padding-right: 0.3rem;
        padding-left: 0.3rem;
      }
      nav h1 {
        text-align: center;
        color: #fff;
        font-size: 0.48rem;
      }
      .ipos_ico{
        padding: 0.3rem 0.25rem;
        margin-right: 0.2rem
      }
      .ipos_ico_leftArrow{
        background: url("data:image/png;base64,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") no-repeat left center;
        background-size: 60%;
      }
      .error .errorTop{
        text-align: center;
        padding-top: 1.47rem;
        padding-bottom: 2rem;
      }
      .error .errorTop p{
        margin-top:3rem;
        font-size: 0.4rem;
        padding:0 0.533rem;
        line-height: 0.6rem;
      }
      .error404{
        width: 1.87rem;
        height: 1.93rem;
        background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIwAAACMCAMAAACZHrEMAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3FpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDowZWZlNjRjZS0yOTRkLTVmNDYtOGNkMi1mMjUyMDc1NGQyMjAiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NDEwMDQ5N0FCOUMyMTFFNTg2OTVDOTA3RERDNDVENjkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NDEwMDQ5NzlCOUMyMTFFNTg2OTVDOTA3RERDNDVENjkiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjkzZjRmNDljLWIyM2MtNmI0OC1iNTkwLTA5YzFjM2E2NzFjZiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDowZWZlNjRjZS0yOTRkLTVmNDYtOGNkMi1mMjUyMDc1NGQyMjAiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4pA2oCAAAAwFBMVEXHx8ft7e3IyMjv7+/+/v7CwsL9/f3Gxsb4+Pj8/Pz7+/v6+vrFxcXNzc35+fnKysr39/fT09PDw8PExMTLy8v29vb19fXk5OT////Q0NDx8fHu7u709PTy8vLs7Ozw8PDJycnX19fz8/PPz8/l5eXMzMzW1tbb29vR0dHf39/Ozs7U1NTh4eHc3NzY2Njg4ODe3t7r6+vV1dXm5ubi4uLq6uro6OjZ2dnp6enS0tLn5+fj4+Pa2trd3d3BwcH////BM+4uAAAAQHRSTlP///////////////////////////////////////////////////////////////////////////////////8AwnuxRAAABitJREFUeNrMnF1z4joMhlUYT27IZPIxw+RckeErpaVlaXc5tCX+///qcKBLZMdJbCkh1c3uzhLzIMvya9kOSJa9yYNMN8skSfw0fJFTXmtAffAxywNRVA3me/nvHWF+y9ArGi2an312B5gPCYWVibBvmCwuXCxf9AZzmhXONsl6gdl7BcmidNE1zCou6Cbfu4R54qD8b/POYDKvYJs4dgNTG7ZRMA/lo/xz/dj5j7VMl5O6T3sZH8YX5qY3tU+8HkdmnBETZmv8ocGp9UfOja4MOTAbg1sS24GaC1fnNMJU3CK8vVNKnVV4xCMN5lRpaOY827xvKkkhpMCEOkqwI+mCCk7gDuNrTcwk2RKtsyauMIE2kg8sCaelqmjqBKNpllQy7UV1jnCB8ezc6tRXFoMK2v2ylJ1Y2O4bE4yazVeyKxNtcQNt0Sa7s9/Q0vlVmKXyxB/ZpSlte+0wqdM862rHRhod5mCXK8m2x+37LTCCMj87GabJGmHifv1y0dP1wwNqJ6SR7MnC2iEFdQHj2bbtT+BiE+texSpwUwsTlR+K7ZeBzvg4k4ltDQyeqV+tW77la3iwfgZJyMgMsxa1YW4H4xA3sXF9B20f6AdmjTrq2QCDgtyl2TEJBo/bwAATESdHGgyWTFWYnKrriDBrQ0dA1TGeJMI8SGJHfWkwx+p/9esZPGBAgxHkNQkZBo2YtQLzZExC/cIgqZ0oMBP6XE2NGUXbYJgXumPGdM8g1wQIZsIQVAyYTHPNpYU3ygTZAQxywuoGk3KW9xyYUA0PUGWpvC8M+uZFBUawYB7cHy4l1P4b5siqNrA8IxVpCcq09DkcTHGFWbBCZsyD8fGUCJiOVBPiwbzjDAd4NS7vD6P0E+AJmwnzQHm8nBLezzALqqrqxjOZ6pnbv56GgMERCygly0FgRPk8cOOXGzOoDHqGiXllhzHXMzmGMRcE7tdNpbDbIphwGBgEALvb3w9Dw2wg41Z8uTDjshQPZfy8DTOaSu05A39oz5QCCyAYHObmDg+AIzm7yDPlcsADj7x86ypmQgPMZKhu+lEw0gAT/wDPlAH8z0AwxxJmNvjQnpcwy+5giKMpKZNeuej/GCjPeOV0IAfvppvuTGCrV/mGkxA+cBcHbJhped4RmIvbLj1z1sCCuSfJHU3lUukTym0xMYxnfOyZfOBF3KScjwAtdg+D5Bm05Y7X2sEQMbNFoxmYtU52NyVqFWL0k4pFGe/cTlcwoNX0SPoKWFIxxJ4AyQ0a37seP/BIh0qEVnpFFRpKP40lwxZ6HVg+FlwdTLZcKchoGxnPd4YRyjeDstqtHDzq2b7UcL3AvBbcZSVXJV5PX4DmrfyuMKZtQbTJM7knS6DVw0FHTO/HshOaYACtLHFP14z0L/2GeaUd5GHZVujJ9u/stiQPqCzxL7Y8Oj4Ila+EamA7aqyE2MHo8OCTDoNOMr/QBLWj0ouqk9AN5pV6TNun6Rl0Dm9fgZEb4glTGsze9NvBNGm5HNMjwqDjZgcTTFiQRhQpZsB4oBX/nJgUNhTPJK3HKZWOst8JW7rvt+JDuHkdTFoQEvFcxBezv+OzLmoOS4K5okXfC3OSd1qxQYWZ4iPk+35QnkXtIXKolTuumZjgF13l6jCbnm5j/LX3pksZ0JAByGU+K6FpyGZQX5btQdwcmq8VGTJV3NuljLDlSo4B5jNS+nXaGYt6zS6zglE7trNB9azeyjSFoxFmR7nf3GIr7cK9tIXRfeOd2CyJzc1OsEiT/HtxT1Fhc7UNLNTPJY6PdJQv/eUNtSrHRv9c51fiXLXVb2+LZ3cYdWa4hA4FZ6RfTG6QPWCbL6+LCscixcoTLq8eaNaKhnvso0dikrtOR41Vwxbharzhb3NXerox3atv+bZWFW1+DQMcm947sZoZ31IQt4mAdkmfRzXvVhjN9/rbbz7kKg1Ezefb51yb9cWy+dUls+UmTTd+4Immj422shMYeQq4L8ywW4fZrrwCHsqvTmHkmozjWW8CuKzWfQqKTaxQYM5T3sQRxU3RO+9YvdjzOL/2irJ9dkpFK0jyRWiYuP/7Jo+1yW2U2r4WqCOYbz0ocz+ZjbyLwSzxU7njtPefAAMAJLJCviciO1gAAAAASUVORK5CYII=") no-repeat;
        background-size: 100%;
        position: absolute;
        z-index: 999;
        top: 3rem;
        left: 4.1rem;
      }
    </style>
    <script>
      !function(a, b){function c(){var b = f.getBoundingClientRect().width; b / i > 540 && (b = 540 * i); var c = b / 10; f.style.fontSize = c + "px", k.rem = a.rem = c}var d, e = a.document, f = e.documentElement, g = e.querySelector('meta[name="viewport"]'), h = e.querySelector('meta[name="flexible"]'), i = 0, j = 0, k = b.flexible || (b.flexible = {}); if (g){console.warn("将根据已有的meta标签来设置缩放比例"); var l = g.getAttribute("content").match(/initial\-scale=([\d\.]+)/); l && (j = parseFloat(l[1]), i = parseInt(1 / j))} else if (h){var m = h.getAttribute("content"); if (m){var n = m.match(/initial\-dpr=([\d\.]+)/), o = m.match(/maximum\-dpr=([\d\.]+)/); n && (i = parseFloat(n[1]), j = parseFloat((1 / i).toFixed(2))), o && (i = parseFloat(o[1]), j = parseFloat((1 / i).toFixed(2)))}}if (!i && !j){var p = (a.navigator.appVersion.match(/android/gi), a.navigator.appVersion.match(/iphone/gi)), q = a.devicePixelRatio; i = p?q >= 3 && (!i || i >= 3)?3:q >= 2 && (!i || i >= 2)?2:1:1, j = 1 / i}if (f.setAttribute("data-dpr", i), !g)if (g = e.createElement("meta"), g.setAttribute("name", "viewport"), g.setAttribute("content", "initial-scale=" + j + ", maximum-scale=" + j + ", minimum-scale=" + j + ", user-scalable=no"), f.firstElementChild)f.firstElementChild.appendChild(g);  else{var r = e.createElement("div"); r.appendChild(g), e.write(r.innerHTML)}a.addEventListener("resize", function(){clearTimeout(d), d = setTimeout(c, 300)}, !1), a.addEventListener("pageshow", function(a){a.persisted && (clearTimeout(d), d = setTimeout(c, 300))}, !1), "complete" === e.readyState?e.body.style.fontSize = 12 * i + "px":e.addEventListener("DOMContentLoaded", function(a){e.body.style.fontSize = 12 * i + "px"}, !1), c(), k.dpr = a.dpr = i, k.refreshRem = c, k.rem2px = function(a){var b = parseFloat(a) * this.rem; return"string" == typeof a && a.match(/rem$/) && (b += "px"), b}, k.px2rem = function(a){var b = parseFloat(a) / this.rem; return"string" == typeof a && a.match(/px$/) && (b += "rem"), b}}(window, window.lib || (window.lib = {}));
      !function(){var a = "html{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}html *{outline:0;-webkit-text-size-adjust:none;-webkit-tap-highlight-color:rgba(0,0,0,0)}html,body{font-family:sans-serif}body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td,hr,button,article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{margin:0;padding:0}input,select,textarea{font-size:100%}table{border-collapse:collapse;border-spacing:0}fieldset,img{border:0}abbr,acronym{border:0;font-variant:normal}del{text-decoration:line-through}address,caption,cite,code,dfn,em,th,var{font-style:normal;font-weight:normal}ol,ul{list-style:none}caption,th{text-align:left}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal}q:before,q:after{content:''}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sup{top:-.5em}sub{bottom:-.25em}a:hover{text-decoration:none}ins,a{text-decoration:none}", b = document.createElement("style"); if (document.getElementsByTagName("head")[0].appendChild(b), b.styleSheet)b.styleSheet.disabled || (b.styleSheet.cssText = a);  else try{b.innerHTML = a} catch (c){b.innerText = a}}();
    </script>
  </head>
  <body class="error">
    <nav>
      <a onclick="window.history.back()" class="leftIco"><i class="ipos_ico ipos_ico_leftArrow"></i> </a>
      <h1>出错啦</h1>
    </nav>
    <div class="errorTop">
      <i class="error404"></i>
      <p><?php echo $message; ?></p>
</div>
</body>
</html>