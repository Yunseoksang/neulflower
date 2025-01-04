
<!-- qr 코드 리더 -->
<script src="./contents/qr-code-scanner1/src/qr_packed.js?ver=<?=time()?>"></script> 
<!-- TTS -->
<script src="./contents/qr-code-scanner1/src/tts.js?ver=<?=time()?>"></script> <!-- QR code reader -->



<!-- 라이트 박스 -->
  <link rel="stylesheet" href="./js/colorbox-master/example1/colorbox.css" />
  <script src="./js/colorbox-master/jquery.colorbox.js"></script>
  <script>
			$(document).ready(function(){
				//라이트박스 입히기
				$(".inline_qr").colorbox({
                    inline:true, 
                    width:"400px", 
                    height:"400px",

                    //라이트박스 닫을때 카메라 중단시키기
                    onClosed: function () {
                        video.srcObject.getTracks().forEach(track => {
                        track.stop();
                        });
                        video.srcObject = null;
                        canvasElement.hidden = false;
                        btnScanQR.hidden = false;

                    }
                });
				
			});
		</script>

  <p class="hide"><a id='inline_qr' class='inline_qr' href="#inline_content">Inline HTML</a></p>



		<!-- This contains the hidden content for inline calls -->
		<div style='display:none'>
			<div id='inline_content' style='padding:10px; background:#fff;'>
       

        <div id="container" style="text-align:center;" >
          <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback" style="margin-bottom:10px;">
                      <input type="text" class="form-control has-feedback-left" id="barcode_input" placeholder="QR코드입력">
                      <span class="fa fa-qrcode form-control-feedback left" aria-hidden="true"></span>
          </div>

          <a id="btn-scan-qr" style="width:100%; text-align:center; cursor:pointer;">
            <img class="qr_logo" src="./contents/sj/storage_out/img/qrcode.png" title="클릭! QR코드 읽기">

          </a>

          <a>
            <canvas hidden="" id="qr-canvas" style="width:100%;"></canvas>
            <div id="qr-result" hidden="">
              <b>Data:</b> <span id="outputData"></span>
            </div>
          </a>


        </div>


			</div>
		</div>

<!-- 카메라 작옹하여 qr 코드 추출하기 --->
<!-- canvas 가 정의된 html 아래쪽에 아래 js 파일이 위치해야 함 -->
<script src="./contents/qr-code-scanner1/src/qrCodeScannerStart.js?ver=<?=time()?>"></script> <!-- QR code reader -->
