const loader = document.createElement('div')
loader.classList.add('preloader')
loader.innerHTML = `<div class="lds-ring"><div></div><div></div><div></div><div></div></div>`
window.start_loader = function(){
    document.querySelectorAll('.preloader').forEach(el => { el.remove() })
    document.body.appendChild(loader)
}
window.end_loader = function(){
    document.querySelectorAll('.preloader').forEach(el => { el.remove() })
}
window.addEventListener("beforeunload", function(e){
    e.preventDefault()
    start_loader()
})
window.onload = function(e){
    e.preventDefault()
    end_loader()
}
$(document).ready(function(){


/**
 * Close Form Modal
 */
$('#FormModal').on('hide.bs.modal', function(e){
    var _this = $(this)
    _this.find('.modal-title').html("")
    _this.find('.modal-body').html("")
    _this.find('.submit-btn').attr("form", "")
})


/**
 * Add New Note
 * - opens the note form modal
 */
$('#add_new_note').click(function(e){
    e.preventDefault()
    var formModal = $('#FormModal')
    $.ajax({
        url:"modal_contents/manage_note.php",
        error:err => {
            console.error(err)
            alert("An error occurred while fetching the modal contents.")
        },
        success: function(resp){
            formModal.find('.modal-title').html("Create New Note")
            formModal.find('.modal-body').html(resp)
            // formModal.find('.submit-btn').attr("form", "manage_note")
            formModal.modal('show')
            formModal.find('.submit-btn').click(function(evt){
                evt.preventDefault()
                formModal.find("form").submit()
            })
        }
    })
    
})

$('.edit-note').click(function(e){
    e.preventDefault()
    var formModal = $('#FormModal')
    $.ajax({
        url:"modal_contents/manage_note.php",
        method:"POST",
        data:{id:$(this).attr('data-id')},
        error:err => {
            console.error(err)
            alert("An error occurred while fetching the modal contents.")
        },
        success: function(resp){
            formModal.find('.modal-title').html("Edit Note Details")
            formModal.find('.modal-body').html(resp)
            // formModal.find('.submit-btn').attr("form", "manage_note")
            formModal.modal('show')
            formModal.find('.submit-btn').click(function(evt){
                evt.preventDefault()
                formModal.find("form").submit()
            })
        }
    })
    
})


/**
 * Edit Note
 */

    $('.note-item').on('mouseenter focus', function(e){
        $(this).find('.card-title').removeClass('text-truncate')
    })
    $('.note-item').on('mouseleave focusout', function(e){
        $(this).find('.card-title').addClass('text-truncate')
    })
    /**
     * Pin/Unpin note
     */
    $('.pin-note').click(function(e){
        e.preventDefault()
        start_loader()
        var id = $(this).attr('data-id')
        var pinned = $(this).attr('data-is-pinned')
        $.ajax({
            url:"ajax-api.php?action=pin_note",
            method:"POST",
            data:{id:id, pinned: pinned},
            dataType:'json',
            error:err=>{
                alert("An error occurred while pinning/unpinning the note.")
                end_loader()
                console.error(err)
            },
            success:function(resp){
                if(typeof resp === "object"){
                    if(resp.status == "success"){
                        location.reload()
                    }else{
                        if(!!resp.error){
                            alert(resp.error)
                        }else{
                            alert("An error occurred while pinning/unpinning the note.")
                        }
                        end_loader();
                    }
                }
            }
        })
    })

    /**
     * Delete Note
     */

    $('.delete-note').click(function(e){
        e.preventDefault()
        var id = $(this).attr('data-id')
        if(confirm(`Are you sure to delete this note? This action cannot be undone.`) === true){
            start_loader()

            $.ajax({
                url:"ajax-api.php?action=delete_note",
                method:"POST",
                data:{id: id},
                dataType:'json',
                error: err =>{
                    alert("Deleting Note failed due to an error occurred.")
                    end_loader();
                    console.error(err)
                },
                success: function(resp){
                    if(typeof resp ==="object"){
                        if(resp.status == 'success'){
                            location.reload()
                        }else{
                            if(!!resp.error){
                                alert(resp.error)
                            }else{
                                alert("Deleting Note failed due to an error occurred.")
                            }
                            end_loader();
                            console.error(resp)
                        }
                    }
                }
            })
        }
    })

    /**
     * View Note
     */
    $('.view-note').click(function(e){
        e.preventDefault()
        var viewModal = $('#ViewModal')
        start_loader()
        $.ajax({
            url:"ajax-api.php?action=get_note",
            method:"POST",
            data:{id: $(this).attr('data-id')},
            dataType:"json",
            error: err=>{
                alert("An error occurred while fetching the note data.")
                end_loader();
                console.error(err)
            },
            success: function(resp){
                if(typeof resp === "object"){
                    viewModal.find(".modal-title").text(resp.title)
                    viewModal.find(".modal-body").html(`<div class="container-fluid">
                    <div>${resp.description}</div>
                    <div class="text-end"><small class="text-muted fst-italic">Note Created: ${resp.created_at}</small></div>
                    </div>`)
                    viewModal.modal('show');
                    end_loader();
                }else{
                    alert("An error occurred while fetching the note data.")
                    end_loader();
                    console.error(resp)
                }
            }
        })
    })
})