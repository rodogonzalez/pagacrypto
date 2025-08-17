import Quagga from "quagga"; // ES6
import '../static/sass/app.scss';
import fxBeepOK from "./components/sounds/ok-beep.mp3";
const SoundBeepOk = new Audio(fxBeepOK);

const cameraHandler = Quagga;
const play_sound_detected = false;
let id_input = null;
let camera_tag=null;


function playAudio() {
    if (play_sound_detected) SoundBeepOk.play();
  }

  function refresh_camera(){
    open_camera(camera_tag, id_input);

  }

function open_camera(id_tag, id_value_field) {
    id_input=id_value_field;
    camera_tag= id_tag;

    cameraHandler.init(
      {
        //frequency: refresh_rate,
        locate: true,
        inputStream: {
          name: "Live",
          type: "LiveStream",
          target: document.querySelector(id_tag), // Or '#yourElement' (optional)
        },
        decoder: {
          readers: [
            "code_128_reader",
            "ean_reader",
            "ean_8_reader",
            "code_39_reader",
            "code_39_vin_reader",
            "codabar_reader",
            "upc_reader",
            "upc_e_reader",
            "i2of5_reader",
            "2of5_reader",
            "code_93_reader",
          ],
        },
      },
      function (err) {
        if (err) {
          console.log(err);
          return;
        }
        cameraHandler.start();
      }
    );





  }

  cameraHandler.onDetected(function (result) {
    var code = result.codeResult.code;
    //console.log(code);
    var container_input = document.getElementById(id_input);
    container_input.value = code;
    playAudio();
    cameraHandler.stop();
    setTimeout(refresh_camera, 1000);
    //bar_code.value = code;

  });

  open_camera("#camera_preview","barcode");
