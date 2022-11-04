function editModalSetting(title = "Update", button = "Edit", page) {
    // $(".updateFormInit").attr('id', "updateForm_"+page);
    $(".allEditModalTitle").text(title);
    $(".allEditModalBtn").text(button);
}

// type is either 1 (success) or 0 (error)
// msg is the text to display on the notification
const sureNotify = (type,msg) => {

    if (type == 1) {
        Snackbar.show({
            text: msg,
            pos: 'top-right',
            actionTextColor: '#fff',
            backgroundColor: '#1abc9c'
        });
    } else {
        Snackbar.show({
            text: msg,
            pos: 'top-right',
            actionTextColor: '#fff',
            backgroundColor: '#e7515a'
        });
    }

}

// init wait me library
function run_waitMe(el, num, effect){
    text = 'Please wait...';
    fontSize = '';
    switch (num) {
        case 1:
            maxSize = '';
            textPos = 'vertical';
            break;
        case 2:
            text = '';
            maxSize = 30;
            textPos = 'vertical';
            break;
        case 3:
            maxSize = 30;
            textPos = 'horizontal';
            fontSize = '18px';
            break;
    }
    el.waitMe({
        effect: effect,
        text: text,
        bg: 'rgba(255,255,255,0.7)',
        color: '#000',
        maxSize: maxSize,
        waitTime: -1,
        source: 'img.svg',
        textPos: textPos,
        fontSize: fontSize,
        onClose: function(el) {}
    });
}