<?php
include 'handlers/questionnaire_handler.php';

?>
<nav class="main-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mt-5">
                <div class="card card-info card-primary">
                    <div class="card-header">
                        <b>Question Form</b>
                    </div>
                    <div class="card-body">
                        <form action="" id="manage-question">
                            <input type="hidden" name="academic_id" value="<?php echo isset($id) ? $id : '' ?>">
                            <input type="hidden" name="id" value="">
                            <div class="form-group">
                                <label for="">Criteria</label>
                                <select name="criteria_id" id="criteria_id" class="custom-select custom-select-sm select2">
                                    <option value=""></option>
                               
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['criteria'] ?></option>
                                
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Question</label>
                                <textarea name="question" id="question" cols="30" rows="4" class="form-control" required=""><?php echo isset($question) ? $question : '' ?></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end w-100">
                            <button class="btn btn-sm btn-success btn-flat bg-gradient-success mx-1" form="manage-question">Save</button>
                            <button class="btn btn-sm btn-flat btn-secondary bg-gradient-secondary mx-1" form="manage-question" type="reset">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 mt-2">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <b>Evaluation Questionnaire for Academic: </b>
                        <div class="card-tools">
                            <button class="btn btn-sm btn-flat btn-secondary bg-gradient-secondary mx-1" id="eval_restrict" type="button">Evaluation Restriction</button>
                            <button class="btn btn-sm btn-flat btn-success bg-gradient-success mx-1" form="order-question">Save Order</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <fieldset class="border border-info p-2 w-100">
                           <legend  class="w-auto">Rating Legend</legend>
                           <p>5 = Strongly Agree, 4 = Agree, 3 = Uncertain, 2 = Disagree, 1 = Strongly Disagree</p>
                        </fieldset>
                        <form id="order-question">
                        <div class="clear-fix mt-2"></div>
                        <table class="table table-condensed">
                            <thead>
                                <tr class="bg-gradient-secondary">
                                    <th colspan="2" class=" p-1"><b></b></th>
                                    <th class="text-center">5</th>
                                    <th class="text-center">4</th>
                                    <th class="text-center">3</th>
                                    <th class="text-center">2</th>
                                    <th class="text-center">1</th>
                                </tr>
                            </thead>
                            <tbody class="tr-sortable">
                                <tr class="bg-white">
                                    <td class="p-1 text-center" width="5px">
                                        <span class="btn-group dropright">
                                          <span type="button" class="btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                           <i class="fa fa-ellipsis-v"></i>
                                          </span>
                                          <div class="dropdown-menu">
                                             <a class="dropdown-item edit_question" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Edit</a>
                                              <div class="dropdown-divider"></div>
                                             <a class="dropdown-item delete_question" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete  </a>
                                          </div>
                                        </span>
                                    </td>
                                    <td class="p-1" width="40%">
                                        
                                        <input type="hidden" name="qid[]" value="">
                                    </td>
                                    
                                    <td class="text-center">
                                        <div class="icheck-success d-inline">
                                            <input type="radio" name="qid[">
                                            <label for="qradio">
                                            </label>
                                      </div>
                                    </td>
                                    
                                </tr>
                                
                            </tbody>
                        </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>