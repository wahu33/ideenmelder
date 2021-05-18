<style>
#dialog_defect {
    display: none;
    position: absolute;
    top: 30px;
    left: 40px;
    width: 500px;
    z-index: 1200;
    background-color: #efefef;
}

#description {
    width: 28em;
    height: 10em;
}
</style>


<div id="dialog_defect" class="card" title="Eintrag editieren">
    <div class="card-header">Eintrag editieren
        <span id="hint"></span>
        <span id="close" type="button" class="close right text-danger">
        <i class="fa fa-window-close"></i>
        </span>
    </div>

    <div class="card-body">
        <form id="editobjectform"  enctype="multipart/form-data" action="#" method="post" >
                    

<!-- Beschreibung  ----------------------------------   --> 

<label for="description"><strong>Beschreibung:</strong></label><br>
<textarea name="description" id="description" maxlength="1001" required="required"></textarea>


<!-- Mängelkategorie  --------------------------------   --> 

<?php if ($boolDefect): ?>
<p id="defect">
    <label for="defect"><strong>Mängelkategorie:</strong></label>
        <select name="defect">
<?php
        foreach ($arrDefect as $defectKey => $defectVal) {
            echo " <option value='$defectKey'>$defectVal</option>";
        }             
?>                   
        </select>
</p>
<?php endif; ?>

<!-- Dateiupload  ----------------------------------   -->

<?php if ($boolUpload):  ?>          
        <label><strong>Bildupload:</strong></label>
        <input type="file" id="photo" name="uploadfile" size="60" maxlength="255" />
 <?php endif;  ?> 

<!-- Einverständnis  ----------------------------------   --> 
  
<input type="hidden" id="loc_id" name="loc_id" value="">
<input type="submit" id="submit" class="btn btn-primary" tabindex="-1">
</form>
    </div> <!-- card-body -->
</div> <!-- card -->

