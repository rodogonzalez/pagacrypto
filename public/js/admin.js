//import {Quagga} from "quagga/dist/quagga.min.js"; // ES6
//import '../static/sass/app.scss';
//import fxBeepOK from "./components/sounds/ok-beep.mp3";
//const SoundBeepOk = new Audio(fxBeepOK);

const cameraHandler = Quagga
const play_sound_detected = false
let id_input = null
let camera_tag = null

var element_camera_preview = document.getElementById('camera_preview')
var element_barcode = document.getElementById('camera_preview')
var element_map_locater = document.getElementById('map_locater')

const map_key = 'vWfLKhUw8HAs1XSRdInf'

let default_lat = 0 // 9.748916999999999
let default_lng = 0 //-83.753428

function playAudio () {
    //if (play_sound_detected) SoundBeepOk.play();
}

function refresh_camera () {
    open_camera(camera_tag, id_input)
}

function open_camera (id_tag, id_value_field) {
    id_input = id_value_field
    camera_tag = id_tag
    //return ;

    cameraHandler.init(
        {
            //frequency: refresh_rate,
            locate: true,
            inputStream: {
                name: 'Live',
                type: 'LiveStream',
                target: document.querySelector(id_tag) // Or '#yourElement' (optional)
            },
            decoder: {
                readers: [
                    'code_128_reader',
                    'ean_reader',
                    'ean_8_reader',
                    'code_39_reader',
                    'code_39_vin_reader',
                    'codabar_reader',
                    'upc_reader',
                    'upc_e_reader',
                    'i2of5_reader',
                    '2of5_reader',
                    'code_93_reader'
                ]
            }
        },
        function (err) {
            if (err) {
                console.log(err)
                return
            }
            cameraHandler.start()
        }
    )
}

cameraHandler.onDetected(function (result) {
    var code = result.codeResult.code
    //console.log(code);
    var container_input = document.getElementById(id_input)
    container_input.value = code
    playAudio()
    cameraHandler.stop()
    setTimeout(refresh_camera, 1000)
    //bar_code.value = code;
})
function get_current_location () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            default_lat = position.coords.latitude
            default_lng = position.coords.longitude
            console.log("Located at: " + default_lat + ' ' + default_lng)
            init_map()
        })
    } else {
        alert('Location not found, using default')
        let default_lat = 9.748916999999999
        let default_lng = -83.753428
        init_map()
    }
}
function init_map () {
    const map_container = document.getElementById('map_locater')

    const map = L.map('map_locater')

    

    //map.locate({ setView: true, maxZoom: 18 })

    if (document.getElementById('position_lng').value != '') {
        default_lng = document.getElementById('position_lng').value
    } else {
        document.getElementById('position_lng').value = default_lng
    }
    if (document.getElementById('position_lat').value != '') {
        default_lat = document.getElementById('position_lat').value
    } else {
        document.getElementById('position_lat').value = default_lat
    }

    const tiles = L.tileLayer(
        'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 18
            
        }
    ).addTo(map)

    const marker = L.marker([default_lat, default_lng], {
        draggable: true
    }).addTo(map);

    map.setView([default_lat, default_lng], 18);
    //alert("hola:)");

    marker.on('dragend', function (event) {
        var position = marker.getLatLng()
        document.getElementById('position_lng').value = position.lng
        document.getElementById('position_lat').value = position.lat
    })
}

if (element_camera_preview && element_barcode) {
    open_camera('#camera_preview', 'barcode')
}

if (element_map_locater) {
    get_current_location()
}
