
if(!dojo._hasResource["mikrotik.Translator"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["mikrotik.Translator"] = true;
dojo.provide("mikrotik.Translator");

(function(){

    var m          = mikrotik.Translator;

    m.tr           = function(tr_data){

        var module      = tr_data.module;
        var phrase      = tr_data.phrase;
        var lang        = tr_data.lang;
        dojo.require('translations.'+tr_data.module);

        //Do the first test
        if(translations[tr_data.module] == undefined){
            return phrase;
        }
        //Check for the phrases object
        if(translations[tr_data.module]['phrases'] == undefined){
             return phrase;
        }
        //Check if the phrase exists
        if(translations[tr_data.module]['phrases'][tr_data.phrase] == undefined){
            return phrase;
        }
        //Check if the language's phrase exists
        if(translations[tr_data.module]['phrases'][tr_data.phrase][tr_data.lang] == undefined){
            return phrase;
        }
        var return_phrase = translations[tr_data.module]['phrases'][tr_data.phrase][tr_data.lang];
        return return_phrase;
    }
})();

}
