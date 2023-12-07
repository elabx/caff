// Blank
htmx.on("htmx:load", function(evt) {

    let tag_selects = document.querySelectorAll('.tag-select');
    let tag_value = document.querySelector('#filter-submit');

    tag_selects.forEach(function(item){
        item.addEventListener('change', function(e){
            let picked = e.currentTarget.value;
            let selector = "#filter-submit option[value=" + picked +  "]";
            let option = document.querySelector(selector);
            //console.log(option);
            option.selected = true;
            option.setAttribute('selected', 'selected');
            option.parentElement.dispatchEvent(new Event('change'));
        });
    })

    /*let view_toggles = util.$$('.events-view-toggles-mobile input[type=radio]');
    //var view_toggles_desktop = util.$('.view-toggles-desktop');
    util.on(view_toggles, 'change', function(){
        let selector = "input[name=view][value=" +  this.value + "]";
        let radio = util.$(selector);
        radio.checked = true;
        util.trigger(radio, 'change');
    });*/

});
