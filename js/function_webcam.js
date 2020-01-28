var addFilter = function(ctx, filter) {
    var filterWidth = filter.naturalHeight * (4 / 3);
    var middleWidth = (canvas.offsetWidth * 0.4)
    var middelHeight = (canvas.offsetHeight * 0.4)

    switch (filter.src.split('/').pop()) {
        case 'ok.png':
        case 'mdr.png':
        case 'poop.png':
            ctx.drawImage(filter, 0, 0, filterWidth, filter.naturalHeight, 0, 0, middleWidth, middelHeight)
            break;
        case 'vomis.png':
            ctx.drawImage(filter, 0, 0, filterWidth, filter.naturalHeight, middleWidth, middelHeight, middleWidth, middelHeight)
            break;
        case 'arc.png':
            ctx.drawImage(filter, 0, 0, filterWidth, filter.naturalHeight, 0, middelHeight, middleWidth, middelHeight)
            break;
        case 'couronne.png':
            ctx.drawImage(filter, 0, 0, filterWidth, filter.naturalHeight, middleWidth, 0, middleWidth, middelHeight)
            break;
    }
}

window.addEventListener("load", () => {
    var canvas = document.getElementById("canvas")
    var ctx = canvas.getContext('2d');
    var filter = null;
    var takePic = document.getElementById("Take_Pic");
    var render = document.createElement('canvas');
    var renderInterval = null;

    document.getElementById('saveBtn').addEventListener('click', function() {
        if (navigator.msSaveOrOpenBlob) {
            var blobObject = render.msToBlob()
            window.navigator.msSaveOrOpenBlob(blobObject, "image.png");
        } else {
            var elem = document.createElement('a');
            elem.href = render.toDataURL("image/png");
            elem.download = "nom.png";
            var evt = new MouseEvent("click", { bubbles: true, cancelable: true, view: window, });
            elem.dispatchEvent(evt);
        }
    });

    document.body.querySelectorAll(".img-png").forEach(element => {
            element.addEventListener("click", (e) => {
                filter = e.srcElement;
            });
        })
        // Partie upload
    var inputFile = document.getElementById("imgInp");
    inputFile.addEventListener('change', function() {
        var reader = new FileReader();
        reader.readAsDataURL(inputFile.files[0]);
        reader.addEventListener("load", function() {
            var image = document.createElement("img");
            image.src = reader.result;

            image.addEventListener('load', () => {
                clearInterval(renderInterval);
                image.width = image.naturalWidth;
                image.height = image.naturalHeight;
                var ratio = image.width / image.height;
                var maxHeight = 300;
                canvas.height = Math.min(image.naturalHeight, maxHeight);
                canvas.width = canvas.height * ratio;
                canvas.style.width = canvas.width + 'px';
                canvas.style.height = canvas.height + 'px';
                renderInterval = setInterval(() => {
                    ctx.drawImage(image, 0, 0, image.naturalWidth, image.naturalHeight, 0, 0, canvas.width, canvas.height);
                    if (filter) {
                        addFilter(ctx, filter);
                        if (document.getElementById("allButtons").classList.contains('hide')) {
                            document.getElementById("allButtons").classList.remove('hide')
                            document.getElementById("allButtons").classList.add('buttons-galerie')
                        }
                    }
                }, 30);
                takePic.addEventListener("click", () => {
                    var publish = document.getElementById("publish")
                    publish.classList.remove('hide')
                    render.width = canvas.width;
                    render.height = canvas.height;
                    render.style.width = render.width + 'px';
                    render.style.height = render.height + 'px';
                    console.log("blop");
                    render.getContext('2d').drawImage(canvas, 0, 0, render.width, render.height, 0, 0, render.width, render.height);
                    console.log("first");
                    document.getElementById("video-canvas").appendChild(render);
                    publish.addEventListener("click", () => {
                        document.getElementById("base64").value = render.toDataURL("image/png");
                        console.log("deuxieme");

                    })
                })
            })
        })
    });

    navigator.mediaDevices.getUserMedia({ video: true }).then(function(mediaStream) {
            var video = document.getElementById('camera');
            video.srcObject = mediaStream;
            video.onloadedmetadata = function() {
                video.play();
                canvas.width = video.videoWidth / 10 * 5;
                canvas.height = video.videoHeight / 10 * 5;
                canvas.style.width = canvas.width + 'px';
                canvas.style.height = canvas.height + 'px';
                setInterval(() => {
                    ctx.drawImage(video, 0, 0, video.videoWidth, video.videoHeight, 0, 0, canvas.width, canvas.height);
                    if (filter) {
                        addFilter(ctx, filter);
                        if (document.getElementById("allButtons").classList.contains('hide')) {
                            document.getElementById("allButtons").classList.remove('hide')
                            document.getElementById("allButtons").classList.add('buttons-galerie')
                        }
                    }
                }, 30);
                takePic.addEventListener("click", () => {
                    var publish = document.getElementById("publish")
                    publish.classList.remove('hide')
                    render.width = video.videoWidth / 10 * 5;
                    render.height = video.videoHeight / 10 * 5;
                    render.style.width = render.width + 'px';
                    render.style.height = render.height + 'px';
                    render.getContext('2d').drawImage(canvas, 0, 0, render.width, render.height, 0, 0, render.width, render.height);
                    document.getElementById("video-canvas").appendChild(render);
                    publish.addEventListener("click", () => {
                        document.getElementById("base64").value = render.toDataURL("image/png");
                    })
                })
            };
        })
        .catch(function(err) {
            var el = document.getElementById("noCamUpload");
            el.style.display = 'initial';
        });
});