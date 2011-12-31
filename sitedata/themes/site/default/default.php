<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Seal - <? echo ($page_title) ?></title>
    <link rel="stylesheet" type="text/css" href="data/templates/default/style.css" />
    <script type="text/javascript">
      div = null;
      
      function showbox(box){
        div = document.getElementById(box);
        div.style.display='block';
        document.onmousemove = positionbox;
      }
      function positionbox(e){
        if (!document.all) {
          posx = e.layerX;
          posy = e.layerY;
        }else if (document.all) {
          posx = event.clientX + document.body.scrollLeft;
          posy = event.clientY + document.body.scrollTop;
        }
        if (div!=null){
          div.style.top=(posy-5-div.clientHeight)+"px";
          div.style.left=(posx+5)+"px";
        }
        return true;
      }
      
      function hidebox(box){        
        div.style.display='none';
        div = null;
        document.onmousemove = null;
      }
    </script>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <? echo ("<meta name=\"description\" content=\"$meta_description\" />") ?>
    <? echo ("<meta name=\"keywords\" content=\"$meta_keywords\" />") ?>
    <link rel="alternate" type="application/rss+xml" title="RSS" href="http://seal.orakeldel.net/rss.php" />
    <? echo ("<link rel=\"shortcut icon\" href=\"/data/templates/default/favicon.png\">") ?>
  </head> 
  <body>
  <div id="main">
    <div id="header">
      <div id="innerheader">
        <a href="index.php"><span id="logo"></span></a>
        <div id="slogan">seal is <span class="blue"><? echo (isset($e_word)?($e_word):"easy") ?></span> art in linux</div>
      </div>
    </div>
    <div id="content">
      <div id="innercontent">
        <div id="brotkruemel"><? echo ($kruemel) ?></div>
        <br />
        <? echo ($main) ?>
        <br />
      </div>
    </div>
    <div id="footer"><? echo ($footer) ?></div>
  </div>
  
  <!-- Piwik -->
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://stat.orakeldel.net/" : "http://stat.orakeldel.net/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 3);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://stat.orakeldel.net/piwik.php?idsite=3" style="border:0" alt="" /></p></noscript>
<!-- End Piwik Tracking Tag -->
  
  </body>
</html>
