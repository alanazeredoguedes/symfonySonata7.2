$( document ).ready(function() {

    // --------------------------------------------------- //
    //jQuery(".logo").children().css({"max-width": "140px",});

    let page = window.location.href

    if(page.includes('admin/sonata/user/group') || page.includes('admin/sonata/user/user') && ( page.includes('edit') || page.includes('create') ) ) {

        /** ************************************************************************************************ */
        /** ************************************************************************************************ */

        let removeBundles = [
            'ROLE_SONATA_USER_ADMIN_USER',
            'ROLE_SONATA_USER_ADMIN_GROUP',
            'ROLE_SONATA_MEDIA_ADMIN_MEDIA',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY_HAS_MEDIA',
            'ROLE_USER',
            'ROLE_SONATA_ADMIN',
            'ROLE_ADMIN',
            'ROLE_SUPER_ADMIN',
            'ROLE_ALLOWED_TO_SWITCH',
            'SONATA',
            'ROLE_SONATA_PAGE_ADMIN_PAGE',
            'ROLE_SONATA_PAGE_ADMIN_PAGE_EDIT',
        ]

        let removeOptions = [
            'CREATE',
            'EDIT',
            'LIST',
            'DELETE',
            'EXPORT',
            'VIEW',
            'ALL',
        ]

        let allRoles = []
        $('.div-select-roles option').each(function (index, element) {

            let value = element.value

            removeBundles.forEach(function (name) {

                if (value.includes(name)) {
                    value = ''
                }

            })
            if (value !== '') {

                removeOptions.forEach(function (name) {

                    if (value.includes('_' + name)) {
                        value = value.split('_' + name)
                        value = value[0]
                    }
                })

                allRoles.push(value)
            }

        })
        allRoles = [...new Set(allRoles)]

        /** ************************************************************************************************ */
        /** ************************************************************************************************ */

        //console.log(allRoles)

        let options = {
            'CREATE': 'Criar',
            'EDIT': 'Editar',
            'LIST': 'Listar',
            'DELETE': 'Excluir',
            'EXPORT': 'Exportar',
            'VIEW': 'Visualizar',
            'ALL': 'Todas Opções',
        }

        let defaultBundles = {
            'ROLE_SONATA_USER_ADMIN_USER': 'Usuário',
            'ROLE_SONATA_USER_ADMIN_GROUP': 'Grupo de Usuários',
            'ROLE_SONATA_MEDIA_ADMIN_MEDIA': 'Mídia',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY': 'Galeria',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY_HAS_MEDIA': 'Mídia Em Galeria',
        }

        let adminOptions = {
            //'ROLE_USER': 'Role User',
            //'ROLE_SONATA_ADMIN': 'Role Sonata Admin',
            //'ROLE_ADMIN': 'Role Admin',
            'ROLE_SUPER_ADMIN': 'Super Admin',
            //'ROLE_ALLOWED_TO_SWITCH': 'Role Allowed To Switch',
            //'SONATA': 'Sonata',
            //'ROLE_SONATA_PAGE_ADMIN_PAGE_EDIT': 'Role Sonata Page Admin Page',
        }

        let box = ''

        /** ************************************************************************************************ */
        /** ********************************* Admin Bundles ************************************* */

        let option = ''
        Object.entries(adminOptions).forEach(([key, value]) => {

            option += `
             <div>
                <input class="icheckbox_square-blue roleCheckBox" type="checkbox" id="${key}">
                <label class="form-check-label" for="${key}">${value}</label>
             </div>
`

        })

        box += `
           <div class="col-md-3 shadow p-3 mb-5 bg-body rounded" >

                <div class="" style=" padding:20px;">
    
                    <label class=" control-label">Permissões Administrativas</label>
                    <input type="text" readonly="readonly" class="form-control" value="Permissões Administrativas">
                    <label class="control-label" style="padding-top: 10px;">Permissões</label>
    
                    ${option}

                </div>

            </div>         
`

        /** ************************************************************************************************ */
        /** ************************************************************************************************ */


        /** ************************************************************************************************ */
        /** ********************************** Default Bundles ********************************************* */
        Object.entries(defaultBundles).forEach(([key, value]) => {

            let option = ''
            Object.entries(options).forEach(([optionsKey, optionsValue]) => {

                option += `
             <div>
                <input class="icheckbox_square-blue roleCheckBox" type="checkbox" id="${key}_${optionsKey}">
                <label class="form-check-label" for="${key}_${optionsKey}">${optionsValue}</label>
             </div>
`

            })

            box += `
           <div class="col-md-3 shadow p-3 mb-5 bg-body rounded" >

                <div class="" style=" padding:20px;">
    
                    <label class=" control-label">Módulo ${value}</label>
                    <input type="text" readonly="readonly" class="form-control" value="${value}">
                    <label class="control-label" style="padding-top: 10px;">Permissões</label>
    
                    ${option}

                </div>

            </div>         
`
        });
        /** ************************************************************************************************ */
        /** ************************************************************************************************ */


        /** ************************************************************************************************ */
        /** ********************************** Default Bundles ********************************************* */
        Object.entries(allRoles).forEach(([key, value]) => {

            let option = ''
            Object.entries(options).forEach(([optionsKey, optionsValue]) => {

                option += `
             <div>
                <input class="icheckbox_square-blue roleCheckBox" type="checkbox" id="${value}_${optionsKey}">
                <label class="form-check-label" for="${value}_${optionsKey}">${optionsValue}</label>
             </div>
`

            })

            box += `
           <div class="col-md-3 shadow p-3 mb-5 bg-body rounded" >

                <div class="" style=" padding:20px;">
    
                    <label class=" control-label">Módulo ${value.split("_").pop()}</label>
                    <input type="text" readonly="readonly" class="form-control" value="${value.split("_").pop()}">
                    <label class="control-label" style="padding-top: 10px;">Permissões</label>
    
                    ${option}

                </div>

            </div>         
`
        });
        /** ************************************************************************************************ */
        /** ************************************************************************************************ */


        $('.div-roles').children('div.box-primary').children('div.box-header').append('<br><br><div class="row">' + box + '</div>')
        $('.div-roles').children('div.box-primary').children('div.box-body').css('display', 'none')


        /**
         * Faz a seleção no Select
         */
        $('.roleCheckBox').on("click", function () {
            let id = this.id
            if ($(this).is(':checked') == true) {
                $("[value='" + id + "']").prop("selected", "selected");
            } else {
                $("[value='" + id + "']").removeAttr('selected');
            }
        })

        /**
         * Faz a seleção no Input
         */
        $('select option').each(function (index, element) {

            let id = this.value

            if ($(this).is(':selected') == true) {
                $("#" + id).trigger('click')
            }

        })


    }






    const alterRolesPage = false;




/*


if(alterRolesPage){


    let li = jQuery('.editable ul li div label span');


    li.each(function( index ) {

        let regras = $(this).html()

        let remove = [
            'ROLE_SONATA_USER_ADMIN_',
            'ROLE_SONATA_CONFIG_',
            'ROLE_SONATA_DEFINICOES_',
            'ROLE_UNILASALLE_SITE_',
            'ROLE_UNILASALLE_AUTOR_',
            'ROLE_UNILASALLE_CURSO_',
            'ROLE_UNILASALLE_CORPOACADEMICO_',
            'ROLE_UNILASALLE_DOCUMENTO_',
            'ROLE_SONATA_MEDIA_ADMIN_',
        ]

        for (let i = 0; i < remove.length; i++) {
            if(regras.includes(remove[i])) {
                regras = regras.replace(remove[i], '')
            }
        }


        let def = {
            'EDIT': 'Editar',
            'LIST': 'Listar',
            'CREATE': 'Criar',
            'VIEW': 'Visualizar',
            'DELETE': 'Deletar',
            'EXPORT': 'Exportar',
            //'ALL': 'Tudo',
        }




        Object.keys(def).forEach(function(item){

           if(regras.includes(item)){
              regras = regras.replace(item, def[item])
           }

        });



        regras = regras.toLowerCase();

        regras = regras.replaceAll('_', ' ')


        //  console.log(regras);


        $(this).html(regras)

        $(this).css({'text-transform': 'capitalize'})

        //$('.box-body').html('sdads')


        let box = "<div class='col-md-4'> <div class='box box-primary'> <div class='box-header'> <h4 class='box-title'> title </h4> </div> </div> </div> "
        box = box + box + box + box + box;
        //$('.box-body').html(box)

    });




}// FIM IF


    const sobrecarga = false;


    if(sobrecarga){

        let url = '/admin/custom/exibir';

        $(".editable h4").html('');

        $(".box-title").each(function( index ) {
            if($( this ).html().trim() === 'Roles'){
                $( this ).html('Módulos e Permissões')
            }
        });

        //Remove o Title Roles
        $("label").each(function( index ) {
            if($( this ).html().trim() === 'Roles'){
                $( this ).remove()
            }
        });



        $.post( url, function( data ) {
            //console.log(data);

            $(".editable").prepend( data );
        });


        $('.editable ul ').css({"display": "none"});

    }


*/







});