<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])){
    require_once("../Master.php");
    $master = new Master();
    $data = $master->get_note_details($_POST['id']);
    if(!$data){
        throw new ErrorException("Invalid Note ID.");
    }
}

?>

<div class="container-fluid">
    <form action="" method="POST" id="manage_form">
        <input type="hidden" name="id" value="<?= isset($data['id']) ? $data['id'] : "" ?>">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control rounded-0" id="title" name="title" autofocus required="required" value="<?= isset($data['title']) ? $data['title'] : "" ?>">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Note Contents</label>
            <textarea name="description" id="description" cols="30" rows="10" class="form-control rounded-0"><?= isset($data['description']) ? $data['description'] : "" ?></textarea>
        </div>
    </form>
</div>

<script>
    $(function(){
        $('#manage_form').submit(function(e){
            e.preventDefault()
            start_loader();
            var _this = $(this)
            var el = $("<div>")
            el.hide()
            el.addClass("mb-3 alert form-msg rounded-0")
            _this.find(".form-msg").remove()

            $.ajax({
                url: "ajax-api.php?action=save_note",
                method: "POST",
                data: _this.serialize(),
                dataType:"JSON",
                error: err=> {
                    el.addClass("alert-danger")
                    el.text("Saving data failed.")
                    _this.prepend(el)
                    el.show("toggle")
                    end_loader()
                    console.error(err)
                },
                success: function(resp){
                    if(typeof resp === "object"){
                        if(resp.status == 'success'){
                            location.reload()
                        }else{
                            el.text("Saving data failed.")
                            if(!!resp.error)
                                el.text(resp.error);

                            _this.prepend(el)
                            el.show("toggle")
                            end_loader()
                        }
                    }else{
                        el.addClass("alert-danger")
                        el.text("Saving data failed.")
                        _this.prepend(el)
                        el.show("toggle")
                        end_loader()
                        console.error(resp) 
                    }
                }
                
            })
        })
    })
</script>