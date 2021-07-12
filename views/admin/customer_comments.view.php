<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>
<br />
<style class="text/css">
    .modal.modal-wide .modal-dialog {
        width: 90%;
    }
    .modal-wide .modal-body {
        overflow-y: auto;
    }
    .modal.modal-medium .modal-dialog {
        width: 80%;
    }
    .modal-medium .modal-body {
        overflow-y: auto;
    }
    .red-border{
        border:1px solid #f00;
    }
    .zero-border{
        border:1px solid #000;
    }
    .active-button{
        border-left:4px solid #999 !important;
        border-top:4px solid #999 !important;
        border-bottom:4px solid #333 !important;
        border-right:4px solid #333 !important;
    }
</style>
<div id="main-container" class="container"  ng-controller="edit_Customer_Comments">

		<center><h1><b>Customers' Comments</b></h1></center>
        <table>		
			<thead>
				<tr>
					<th style="text-align:center;">Date</th>
					<th style="text-align:center;">Customer's Comment
						<span style="color:yellow">(Click to edit)</span>
					</th>
					<th style="text-align:center;">Display After Comment</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='(key, comment_entry) in commentList'>
					<td style="text-align:center;">{{comment_entry.date}} </td>
					<td style="text-align:center;" ng-click="EditComment(key, comment_entry.id)">{{comment_entry.comment}}</a></td>
					<td style="text-align:center;">{{comment_entry.after_comment}} </td>
				</tr>					
			</tbody>
		</table>
		<br/>
	
    <!--Add Popup Window-->
    <div class="modal fade modal-medium" id="modalEditComment" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmEditComment">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><b>Edit Comment</b></h4>
                    </div>
                    <div class="modal-body">
                    		<div class="row">
                            	<div class="form-group col-md-12">
                                	<label class="control-label"><b>Comment:</b></label><br />
									<textarea style="font-size: 30px;" type="text" value="" ng-model="editComment.comment" class='form-control' rows='6'></textarea>
                            	</div>                  
                        	</div>
                        	<div class="row">
                            	<div class="form-group col-md-12">
                                	<label class="control-label"><b>After Comment:</b></label><br />
									<textarea style="font-size: 30px;" type="text" value="" ng-model="editComment.after_comment" class='form-control' rows='6'></textarea>
                            	</div>                  
                        	</div>
                		</div>
                		<div class="modal-footer">
                        <button type="button" class="btn btn-primary" ng-click="SaveComment(editAfterComment.id)" ng-disabled="!frmEditComment.$valid">SUBMIT</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">EXIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/edit_Customer_Comments.js"></script>

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>