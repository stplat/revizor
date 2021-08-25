import Vue from 'vue';
import Unicon from 'vue-unicons';
import {
  uniPresentationCheck, uniMapMarker, uniSort, uniAngleDown, uniAngleUp,
  uniVideo, uniMapPinAlt, uniArchiveAlt, uniMultiply, uniTrashAlt, uniPlusSquare, uniPen
} from 'vue-unicons/src/icons';

Unicon.add([
  uniPresentationCheck, uniMapMarker, uniSort, uniAngleDown, uniAngleUp,
  uniVideo, uniMapPinAlt, uniArchiveAlt, uniMultiply, uniTrashAlt, uniPlusSquare, uniPen
]);

Vue.use(Unicon);

export default Unicon;
