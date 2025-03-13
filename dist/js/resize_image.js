function resizeImage(file, maxWidth, maxHeight, quality, callback) {
    let reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function(event) {
        let img = new Image();
        img.src = event.target.result;
        img.onload = function() {
            let canvas = document.createElement('canvas');
            let ctx = canvas.getContext('2d');

            let width = img.width;
            let height = img.height;

            if (width > maxWidth || height > maxHeight) {
                let scale = Math.min(maxWidth / width, maxHeight / height);
                width *= scale;
                height *= scale;
            }

            canvas.width = width;
            canvas.height = height;
            ctx.drawImage(img, 0, 0, width, height);

            canvas.toBlob(function(blob) {
                callback(blob);
            }, 'image/jpeg', quality);
        };
    };
}

console.log("resize_image.js Loaded Successfully");
