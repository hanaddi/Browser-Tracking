<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="favicon.php?v=4<?php echo "-"// . time(); ?>">
  <title>Document</title>
</head>

<body>

</body>

<script>
  async function getCurrentPageFavicon(preferBlob = false) {
    // 1. Try <link rel="icon">
    const links = document.querySelectorAll(
      'link[rel~="icon"], link[rel="shortcut icon"]'
    );

    let faviconUrl = null;

    if (links.length > 0) {
      // Prefer largest size
      let best = links[0];
      let bestSize = 0;

      links.forEach(link => {
        const sizes = link.getAttribute('sizes');
        if (sizes && sizes !== 'any') {
          const size = parseInt(sizes.split('x')[0], 10);
          if (size > bestSize) {
            bestSize = size;
            best = link;
          }
        }
      });

      faviconUrl = best.href;
    } else {
      // 2. Fallback to /favicon.ico
      faviconUrl = `${location.origin}/favicon.ico`;
    }

    // 3. Return URL directly (browser cache will be used automatically)
    if (!preferBlob) {
      return faviconUrl;
    }

    // 4. Optional: convert to Blob (still cache-aware)
    const response = await fetch(faviconUrl, {
      cache: 'force-cache'
    });
    const blob = await response.blob();
    return blob;
    // return URL.createObjectURL(blob);
  }

  function getPixelArrayFromBlob(blob) {
    return new Promise((resolve, reject) => {
      const img = new Image();
      const url = URL.createObjectURL(blob);

      img.onload = () => {
        const canvas = document.createElement('canvas');
        canvas.width = img.width;
        canvas.height = img.height;

        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0);

        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);

        URL.revokeObjectURL(url);
        resolve(imageData); // Uint8ClampedArray
      };

      img.onerror = reject;
      img.src = url;
    });
  }


  window.onload = async function(ev) {
    const blob = await getCurrentPageFavicon(true);
    const arr = await getPixelArrayFromBlob(blob);
    console.log(arr.data.filter((e, i) => i%4!==3));
  }
</script>

</html>