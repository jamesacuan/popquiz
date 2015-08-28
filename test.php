<!DOCTYPE html>
<html lang="en">
<head>
<meta charset=utf-8>
<meta name="viewport" content="width=620">
<title>HTML5 Demo: Drag and drop</title>
<link rel="stylesheet" href="css/html5demos.css">
<script src="js/h5utils.js"></script></head>
<body>
<section id="wrapper">
    <header>
      <h1>Drag and drop</h1>
    </header>

<style type="text/css">
li {
  list-style: none;
}

li a {
  text-decoration: none;
  color: #000;
  margin: 10px;
  width: 150px;
  border: 3px dashed #999;
  background: #eee;
  padding: 10px;
  display: block;
}

*[draggable=true] {
  -moz-user-select:none;
  -khtml-user-drag: element;
  cursor: move;
}

*:-khtml-drag {
  background-color: rgba(238,238,238, 0.5);
}

li a:hover:after {
  content: ' (drag me)';
}

ul {
  margin-left: 200px;
  min-height: 300px;
}

li.over {
  border-color: #333;
  background: #ccc;
}

#bin {
  background: url(images/bin.jpg) top right no-repeat;
  height: 250px;
  width: 166px;
  float: left;
  border: 5px solid #000;
  position: relative;
  margin-top: 0;
}

#bin.over {
  background: url(images/bin.jpg) top left no-repeat;
}

#bin p {
  font-weight: bold;
  text-align: center;
  position: absolute;
  bottom: 20px;
  width: 166px;
  font-size: 32px;
  color: #fff;
  text-shadow: #000 2px 2px 2px;
}
</style>
<article>
  <p>Drag the list items over the dustbin, and drop them to have the bin eat the item</p>
  <div id="bin"></div>
  <ul>
    <li><a id="one" href="#">one</a></li>
    <li><a href="#" id="two">two</a></li>
    <li><a href="#" id="three">three</a></li>
    <li><a href="#" id="four">four</a></li>
    <li><a href="#" id="five">five</a></li>
  </ul>
</article>
  <script>

  var eat = ['yum!', 'gulp', 'burp!', 'nom'];
  var yum = document.createElement('p');
  var msie = /*@cc_on!@*/0;
  yum.style.opacity = 1;

  var links = document.querySelectorAll('li > a'), el = null;
  for (var i = 0; i < links.length; i++) {
    el = links[i];

    el.setAttribute('draggable', 'true');

    addEvent(el, 'dragstart', function (e) {
      e.dataTransfer.effectAllowed = 'copy'; // only dropEffect='copy' will be dropable
      e.dataTransfer.setData('Text', this.id); // required otherwise doesn't work
    });
  }

  var bin = document.querySelector('#bin');

  addEvent(bin, 'dragover', function (e) {
    if (e.preventDefault) e.preventDefault(); // allows us to drop
    this.className = 'over';
    e.dataTransfer.dropEffect = 'copy';
    return false;
  });

  // to get IE to work
  addEvent(bin, 'dragenter', function (e) {
    this.className = 'over';
    return false;
  });

  addEvent(bin, 'dragleave', function () {
    this.className = '';
  });

  addEvent(bin, 'drop', function (e) {
    if (e.stopPropagation) e.stopPropagation(); // stops the browser from redirecting...why???

    var el = document.getElementById(e.dataTransfer.getData('Text'));

    el.parentNode.removeChild(el);

    // stupid nom text + fade effect
    bin.className = '';
    yum.innerHTML = eat[parseInt(Math.random() * eat.length)];

    var y = yum.cloneNode(true);
    bin.appendChild(y);

    setTimeout(function () {
      var t = setInterval(function () {
        if (y.style.opacity <= 0) {
          if (msie) { // don't bother with the animation
            y.style.display = 'none';
          }
          clearInterval(t);
        } else {
          y.style.opacity -= 0.1;
        }
      }, 50);
    }, 250);

    return false;
  });

  </script>
<a id="html5badge" href="http://www.w3.org/html/logo/">
<img src="http://www.w3.org/html/logo/badge/html5-badge-h-connectivity-device-graphics-multimedia-performance-semantics-storage.png" width="325" height="64" alt="HTML5 Powered with Connectivity / Realtime, Device Access, Graphics, 3D & Effects, Multimedia, Performance & Integration, Semantics, and Offline & Storage" title="HTML5 Powered with Connectivity / Realtime, Device Access, Graphics, 3D & Effects, Multimedia, Performance & Integration, Semantics, and Offline & Storage">
</a>
    <footer><a href="/">HTML5 demos</a>/<a id="built" href="http://twitter.com/rem">@rem built this</a>/<a href="#view-source">view source</a></footer>
</section>
<a href="http://github.com/remy/html5demos"><img style="position: absolute; top: 0; left: 0; border: 0;" src="http://s3.amazonaws.com/github/ribbons/forkme_left_darkblue_121621.png" alt="Fork me on GitHub" /></a>
<script src="js/prettify.packed.js"></script>
<script>
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script>
try {
var pageTracker = _gat._getTracker("UA-1656750-18");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>
