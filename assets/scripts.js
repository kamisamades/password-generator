/* Scripts JS */

function updatePasswordPattern() {
    //console.log('updatePasswordPattern() triggered');
    const includeWords = $( '#include-words' ).is( ':checked' );
    const includeRandom = $( '#include-random' ).is( ':checked' );
    const separator = includeWords && includeRandom ? '-' : '';
    let pattern = '';
    if (includeWords && includeRandom) {
        pattern = '{words}' + separator + '{random}';
    }
    else if (includeWords) {
        pattern = '{words}';
    }
    else if (includeRandom) {
        pattern = '{random}';
    }
    if (includeWords && $( '#words' ).val() < 1) {
        $( '#words' ).val(1);
    }
    //console.log('Updated password pattern:', pattern);
    $( '#password-pattern' ).val( pattern );
    updateInterface();
}

function updateInterface() {
    const includeWords = $( '#include-words' ).is( ':checked' );
    const includeRandom = $( '#include-random' ).is( ':checked' );
    if (!includeWords) {
        $( '.words-block').each(function() {
            $(this).addClass('invisible');
        });
    }
    else {
        $( '.words-block').each(function() {
            $(this).removeClass('invisible');
        });
    }
    if (!includeRandom) {
        $( '.random-block').each(function() {
            $(this).addClass('invisible');
        });
    }
    else {
        $( '.random-block').each(function() {
            $(this).removeClass('invisible');
        });
    }
    if (!includeWords && !includeRandom) {
        $( '#generate-btn' ).attr( 'disabled', true );
    }
    else {
        $( '#generate-btn' ).attr( 'disabled', false );
    }
}

function allowGenerate() {
    let allow = $( '#include-words' ).is( ':checked' ) || $( '#include-random' ).is( ':checked' );
    displayMessages(allow);
    return allow;
}

function displayMessages(allow) {
    if (!allow) {
        $( '#generated-passwords' ).addClass('invisible');
        $( '#password-pattern-explanation' ).removeClass('invisible');
        $( '#password-hint' ).removeClass('invisible');
    }
    else {
        $( '#password-pattern-explanation' ).addClass('invisible');
        $( '#password-hint' ).addClass('invisible');
    }
}

function displayPattern() {
    if ($( '#password-pattern-container' ).hasClass('invisible')) {
        $( '#password-pattern-container' ).removeClass('invisible');
        $( '#display-pattern-btn' ).html('Hide Password Pattern');
    }
    else {
        $( '#password-pattern-container' ).addClass('invisible');
        $( '#display-pattern-btn' ).html('Show Password Pattern');
    }
}