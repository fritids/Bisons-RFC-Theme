// Form validation. Empty text fields...

function FormValidator( form, validation_patterns )
{
    this.errorCount = 0;
    this.validation_patterns = typeof validation_patterns === 'undefined' ? [

        {
            name  : 'notblank',
            regex : /^(?=\s*\S).*$/,
            error : 'Field cannot be left empty',
        },
        
        {
            name  : 'needemail',
            regex : /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
            error : 'Not a valid email address',
        },
        
        {
            name  : 'needphonenum',
            regex : /^\+?[0-9\s\(\)]+$/,
            error : 'Not a valid phone number',
        },
        
        {
            name  : 'needpostcode',
            regex : /^[A-Za-z]{1,2}[0-9]{1,2}\s?[0-9]{1}[a-zA-Z]{2}$/,
            error : 'Not a valid UK postcode',
        },
    ] : validation_patterns;
    
    this.form = typeof form === 'undefined' ? jQuery('form') : form;
    this.fields = this.form.find('input, textarea, select');
    
    this.fields.each ( function() {
        
    });
    this.validationErrors = new Array();
}

FormValidator.prototype.fieldSize = function()
{
    var count = 0;
    this.fields.forEach(function()
    {
        count++;
    });  
    return count; 
};

FormValidator.prototype.validate_field = function( formIndex, value, type ) 
{
    this.validation_patterns.forEach = function( pattern_array, index, array) {
        if ( pattern_array.name == value)
        {
            if ( pattern_array.regex.test ( value ) )
            {
                this.validationErrors[formIndex] = false;
                this.fields[formIndex].siblings('.formerror').remove();
            }
            else
            {   
                this.fields[formIndex].siblings('.formerror').remove();
                this.validationErrors[formIndex] = pattern_array.error;
                jQuery('<p class="formerror">' + pattern_array.error + '</p>').insertAfter(this.fields[formIndex]);
            }
        }
    };
};

FormValidator.prototype.validate_fields = function ( )
{



    this.fields.each(function( index, element ) {
        console.log  (parent.fields[index]);    /*
        classList = this.attr('class').split(/\s+/); 
        classList.forEach(function( theClass )
        {
            this.validate_field(index, this.fields[index].val(), theClass );    
        }); */
        });
    
};

var validator = new FormValidator();
validator.validate_fields();

/*
var validation_errors = new Array();
var submitattempt = false;

var check_empty_field = function(event) {
    console.log(event);

    if (jQuery(event).parents(':hidden').length > 0) {
        remove_error(jQuery(this));
        return;
    }

    if (jQuery(this).val() === '') {

        if (event.type == 'focusout' || submitattempt) {

            add_error(jQuery(this), 'Field cannot be left empty');
        }

    } else {
        remove_error(jQuery(this));
    }

    reset_submit_button(jQuery(this));
    return;
};

// SELECT box, most make a selection
var check_unselected = function(event) {
    if (jQuery(this).parents(':hidden').length > 0) {
        remove_error(jQuery(this));
        return;
    }
    if (jQuery(this).val() === '') {
        if (event.type == 'focusout' || submitattempt) {
            add_error(jQuery(this), 'You must make a selection');

        }
    } else {
        remove_error(jQuery(this));
    }
    reset_submit_button(jQuery(this));
    return;
};

// Valid email addy
var check_for_email = function(event) {
    if (jQuery(this).parents(':hidden').length > 0) {
        remove_error(jQuery(this));
        return;
    }
    var email = jQuery(this).val();
    var regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    if (! regex.test(email)) {
        if (event.type == 'focusout' || submitattempt) {
            add_error(jQuery(this), 'Not a valid e-mail address');
        }
    } else {
        remove_error(jQuery(this));

    }

    reset_submit_button(jQuery(this));
    return;
};

// Valid phone number. Looks for numbers only, with an optional + at the start. It allows parenthesis and spaces.
var check_for_phone_num = function(event) {
    if (jQuery(this).parents(':hidden').length > 0) {
        remove_error(jQuery(this));
        return;
    }

    var phone = jQuery(this).val();

    var regex = /^\+?[0-9\s\(\)]+$/;

    if (! regex.test(phone)) {
        if (event.type == 'focusout' || submitattempt) {
            add_error(jQuery(this), 'Not a valid phone number');
            
        }
    } else {
        remove_error(jQuery(this));

    }

    reset_submit_button(jQuery(this));
    return;
};

// Valid UK postcode
var check_for_postcode = function(event) {
    if (jQuery(this).parents(':hidden').length > 0) {
        remove_error(jQuery(this));
        return;
    }

    var postcode = jQuery(this).val();

    var regex = /^[A-Za-z]{1,2}[0-9]{1,2}\s?[0-9]{1}[a-zA-Z]{2}$/;

    if (! regex.test(postcode)) {
        if (event.type == 'focusout' || submitattempt) {
            add_error(jQuery(this), 'Not a valid UK postcode');

        }
    } else {
        remove_error(jQuery(this));
    }

    reset_submit_button(jQuery(this));
    return;
};

var must_be_checked = function(event) {
    if (jQuery(this).parents(':hidden').length > 0) {
        return;
    }

    if (! jQuery(this).is(":checked")) {
        add_error(jQuery(this), 'You must check this box to continue');
    } else {
        remove_error(jQuery(this));
    }

    reset_submit_button(jQuery(this));
    return;
};

function add_error(object, message) {
    if (object.attr('type') == 'checkbox') {
        object = object.parent();
        object.addClass('errorlabel');
    } else {
        object.siblings('label').addClass('errorlabel');
    }

    // Remove old errors
    validation_errors.splice(jQuery.inArray(object, validation_errors), 1);

    object.siblings('.formerror').remove();

    // Add error message node
    jQuery('<p class="formerror">' + message + '</p>').insertAfter(object);

    // Add jQuery object to the errors array so the form knows not to submit
    validation_errors.push(object);
    console.log (validation_errors );
}

function remove_error(object) {

    if (object.attr('type') == 'checkbox') {
        object = object.parent();
        object.removeClass('errorlabel');
    } else {
        object.siblings('label').removeClass('errorlabel');
    }

    object.siblings('.formerror').remove();

    // Remove from the validation errors array
    validation_errors.splice(jQuery.inArray(object, validation_errors), 1);
}

function reset_submit_button(object) {
    if (validation_errors.length > 0) {
        object.closest('form').find('input[type=submit]').attr('disabled', 'disabled');
        object.closest('form').find('button[type=submit]').attr('disabled', 'disabled');
    }

    // If not, re-enable form
    else {
        object.closest('form').find('input[type=submit]').removeAttr('disabled');
        object.closest('form').find('button[type=submit]').removeAttr('disabled');
    }
    
    console.log (validation_errors);
}


jQuery(document).ready(function() {

    jQuery('.notempty').focusout(check_empty_field);
    jQuery('.notempty').keyup(check_empty_field);

    jQuery('.needemail').focusout(check_for_email);
    jQuery('.needemail').keyup(check_for_email);

    jQuery('.needphonenum').focusout(check_for_phone_num);
    jQuery('.needphonenum').keyup(check_for_phone_num);

    jQuery('.needpostcode').focusout(check_for_postcode);
    jQuery('.needpostcode').keyup(check_for_postcode);

    jQuery('.mustselect').focusout(check_unselected);
    jQuery('.mustselect').change(check_unselected);

    jQuery('.mustcheck').focusout(must_be_checked);
    jQuery('.mustcheck').change(must_be_checked);

    jQuery('form').submit(function(e) {

        submitattempt = true;
        jQuery(this).find('.notempty').each(check_empty_field);
        jQuery(this).find('.needemail').each(check_for_email);
        jQuery(this).find('.needphonenum').each(check_for_phone_num);
        jQuery(this).find('.needpostcode').each(check_for_postcode);
        jQuery(this).find('.mustcheck').each(must_be_checked);
        jQuery(this).find('.mustselect').each(check_unselected);
        submitattempt = false;

        if (validation_errors.length > 0) {
            jQuery(this).find('input[type=submit]').attr('disabled', 'disabled');
            jQuery(this).find('button[type=submit]').attr('disabled', 'disabled');
            e.preventDefault();
        } else {
            jQuery(this).find('input[type=submit]').removeAttr('disabled');
            jQuery(this).find('button[type=submit]').removeAttr('disabled');
            e.preventDefault();
        }
    });
}); */