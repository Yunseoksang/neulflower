const qrcode1 = window.qrcode;

const video = document.createElement("video");
const canvasElement = document.getElementById("qr-canvas");
const canvas = canvasElement.getContext("2d");

const qrResult = document.getElementById("qr-result");
const outputData = document.getElementById("outputData");
const btnScanQR = document.getElementById("btn-scan-qr");

let scanning = true;

qrcode1.callback = res => {
  if (res) {

    console.log("결과:"+res);
    go_next_status(res);



    //outputData.innerText = res;

    // if($("#input_qrcode").val() != res){ //바코드가 달라지면 함수호출
    //   $("#input_qrcode").val(res);
    //   get_barcode_product(res);
    // }
    scanning = false;

    video.srcObject.getTracks().forEach(track => {
      track.stop();
    });

    //qrResult.hidden = false;
    canvasElement.hidden = true;
    btnScanQR.hidden = false;


    //colorbox light box 닫기
  }
};

//카메라 작동 멈추기(별도로 추가한 모듈임))
function stopStreamedVideo(videoElem) {
  const stream = videoElem.srcObject;
  const tracks = stream.getTracks();

  tracks.forEach((track) => {
    track.stop();
  });

  videoElem.srcObject = null;
}



btnScanQR.onclick = () => {
  navigator.mediaDevices
    .getUserMedia({ video: { facingMode: "environment" } })
    .then(function(stream) {
      scanning = true;
      //qrResult.hidden = true;
      btnScanQR.hidden = true;
      canvasElement.hidden = false;
      video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
      video.srcObject = stream;
      video.play();
      tick();
      scan();
    });
};




function tick() {
  canvasElement.height = video.videoHeight;
  canvasElement.width = video.videoWidth;
  canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

  scanning && requestAnimationFrame(tick);
}

function scan() {
  try {
    qrcode1.decode();
  } catch (e) {
    setTimeout(scan, 300);
  }
}

