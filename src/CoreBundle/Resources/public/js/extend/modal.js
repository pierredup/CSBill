import $ from 'jquery';
import '../lib/bootstrap/modal';
import '../lib/bootstrap/modalmanager';

$.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner = '\
        <div class="loading-spinner">\
            <div class="progress progress-xs active">\
                <div class="progress-bar progress-bar-aqua progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">\
                    <span class="sr-only">Loading...</span>\
                    </div>\
                </div>\
        </div>';

$.fn.modal.defaults.maxHeight = () => {
    // subtract the height of the modal header and footer
    return $(window).height() - 165;
};

export default $.fn.modal;
