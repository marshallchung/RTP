import './bootstrap';
import Alpine from 'alpinejs'
import { parseXml, xml2json } from './xml2json';
import collapse from '@alpinejs/collapse'
import { Chart, initTE } from "tw-elements";
//import Chart from 'chart.js/auto';
//import XLSX from 'xlsx'
import { Loader } from '@googlemaps/js-api-loader';
initTE({ Chart }, false);
const loader = new Loader({
    apiKey: "AIzaSyC_KVbB4HNmZEyb3Lkp2hgDM94WshmPvWE",
    version: "weekly",
    libraries: ["places"]
});

window.purifyJson = function(json) {
    json = json.replace(/\\n/g, "\\n")
        .replace(/\\'/g, "\\'")
        .replace(/\\"/g, '\\"')
        .replace(/\\&/g, "\\&")
        .replace(/\\r/g, "\\r")
        .replace(/\\t/g, "\\t")
        .replace(/\\b/g, "\\b")
        .replace(/\\f/g, "\\f");

    json = json.replace(/[\u0000-\u0019]+/g, "");
    return json;
}

window.parseXml = parseXml;
window.xml2json = xml2json;
window.loader = loader;
window.Alpine = Alpine;
//window.XLSX = XLSX;
Alpine.plugin(collapse)
Alpine.start();

(function () {
})();
