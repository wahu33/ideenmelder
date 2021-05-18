
    <div id="dialog_comment" class="card" title="Kommentar">
        <div class="card-header">Ihr Kommentar
            <span id="close_comment" type="button" class="close right text-danger">
            <i class="fa fa-window-close"></i>
          </span>

        </div>
        <div class="card-body">
            <form id="commentform"  action="#" method="post" >
                <fieldset style="border: none;">
                    <p>
                        <label for=comment_username"><strong>Ihr Name oder Pseudonym:</strong></label><br>
                        <input type="text" name="comment_username" maxlength="16" id="comment_username" placeholder="Name" class="text" required="required">
                        <input type="hidden" name="loc_id" id="loc_id" value="999">
                    </p>
                    <label for="description"><strong>Kommentar:</strong></label><br>
                    <textarea name="comment" id="comment" maxlength="1001" placeholder="Ihr Kommentar" required="required"></textarea>
                    <br>                  
            
                    <input type="submit" value ="Absenden" class="btn btn-primary" tabindex="-1">
                </fieldset>
            </form>
        </div>
    </div>
