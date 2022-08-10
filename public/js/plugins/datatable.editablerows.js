/**
 *
 * EditableRows
 *
 * Interface.Plugins.Datatables.EditableRows.html page content scripts. Initialized from scripts.js file.
 *
 *
 */

class EditableRows {
  constructor() {
    if (!jQuery().DataTable) {
      console.log('DataTable is null!');
      return;
    }

    // Selected single row which will be edited
    this._rowToEdit;

    // Datatable instance
    this._datatable;

    // Edit or add state of the modal
    this._currentState;

    // Controls and select helper
    this._datatableExtend;

    // Add or edit modal
    this._addEditModal;

    // Datatable single item height
    this._staticHeight = 62;

    this._createInstance();
    this._addListeners();
    this._extend();
    this._initBootstrapModal();
  }

  // Creating datatable instance
  _createInstance() {
    const _this = this;
    this._datatable = jQuery('#datatableRows').DataTable({
      scrollX: true,
      // buttons: ['copy', 'excel', 'csv', 'print'],
      buttons: [{
          extend: 'copy',
          exportOptions: {
            columns: [ 2, 4, 5, 6, 7, 8, 9, 10, 11, 12 ] //Your Column value those you want
          }
        },
        {
          extend: 'excel',
          exportOptions: {
            columns: [ 2, 4, 5, 6, 7, 8, 9, 10, 11, 12 ] //Your Column value those you want
          }
        },
        {
          extend: 'csv',
          exportOptions: {
            columns: [ 2, 4, 5, 6, 7, 8, 9, 10, 11, 12 ] //Your Column value those you want
          }
        },
        {
          extend: 'print',
          exportOptions: {
            columns: [ 2, 4, 5, 6, 7, 8, 9, 10, 11, 12 ] //Your Column value those you want
          }
        }],
      info: false,
      order: [[2, 'desc']], // Clearing default order
      // order: [], // Clearing default order
      sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>', // Hiding all other dom elements except table and pagination
      pageLength: 50,
      columns: [
        {data: 'id', 'visible' : false }, {data: 'name', 'visible' : false }, null,{data: 'model', 'visible' : false },{data: 'price'},{data: 'buydate'},{data: 'enddate'},null,{data: 'plantouse'},null,null,null, {data: 'done'}, null



        // {data: 'Id'},
        // {data: 'Name'},
        // {data: 'Model'},
        // {data: 'Price'},



        // {data: 'BuyDate'},
        // {data: 'EndDate'},
        // {data: 'Months in use'},
        // {data: 'Month price'},


        // {data: 'Plan To Use In Months'}


        // {data: 'Expire after'},
        // {data: 'Current value'},
        // {data: 'Plan month value'},
        // {data: 'Can change'},
        // {data: 'Total years'},
        // {data: 'Extra years'}
      ],
      language: {
        paginate: {
          previous: '<i class="cs-chevron-left"></i>',
          next: '<i class="cs-chevron-right"></i>',
        },
      },
      initComplete: function (settings, json) {
        _this._setInlineHeight();
      },
      drawCallback: function (settings) {
        _this._setInlineHeight();
      },
      columnDefs: [
        // Adding Tag content as a span with a badge class
        {
          targets: 13,
          render: function (data, type, row, meta) {
            return '<div class="form-check float-end mt-1"><input type="checkbox" class="form-check-input"></div>';
          },
        },
      ],
    });
    _this._setInlineHeight();
  }

  _addListeners() {
    // Listener for confirm button on the edit/add modal
    document.getElementById('addEditConfirmButton').addEventListener('click', this._addEditFromModalClick.bind(this));

    // Listener for add buttons
    document.querySelectorAll('.add-datatable').forEach((el) => el.addEventListener('click', this._onAddRowClick.bind(this)));

    // Listener for delete buttons
    document.querySelectorAll('.delete-datatable').forEach((el) => el.addEventListener('click', this._onDeleteClick.bind(this)));

    // Listener for edit button
    document.querySelectorAll('.edit-datatable').forEach((el) => el.addEventListener('click', this._onEditButtonClick.bind(this)));

    // Calling a function to update tags on click
    document.querySelectorAll('.done-yes').forEach((el) => el.addEventListener('click', () => this._updateTag('yes')));
    document.querySelectorAll('.done-no').forEach((el) => el.addEventListener('click', () => this._updateTag('no')));
    // document.querySelectorAll('.tag-sale').forEach((el) => el.addEventListener('click', () => this._updateTag('Sale')));

    // Calling clear form when modal is closed
    // document.getElementById('addEditModal').addEventListener('hidden.bs.modal', this._clearModalForm);
  }

  // Extending with DatatableExtend to get search, select and export working
  _extend() {
    this._datatableExtend = new DatatableExtend({
      datatable: this._datatable,
      editRowCallback: this._onEditRowClick.bind(this),
      singleSelectCallback: this._onSingleSelect.bind(this),
      multipleSelectCallback: this._onMultipleSelect.bind(this),
      anySelectCallback: this._onAnySelect.bind(this),
      noneSelectCallback: this._onNoneSelect.bind(this),
    });
  }

  // Keeping a reference to add/edit modal
  _initBootstrapModal() {
    this._addEditModal = new bootstrap.Modal(document.getElementById('addEditModal'));
  }

  // Setting static height to datatable to prevent pagination movement when list is not full
  _setInlineHeight() {
    if (!this._datatable) {
      return;
    }
    const pageLength = this._datatable.page.len();
    document.querySelector('.dataTables_scrollBody').style.height = this._staticHeight * pageLength + 'px';
  }

  // Add or edit button inside the modal click
  _addEditFromModalClick(event) {
    console.log('aaa');
    if (this._currentState === 'add') {
      this._addNewRowFromModal();
    } else {
      // this._editRowFromModal();
      this._addNewRowFromModal();
    }
    this._addEditModal.hide();
  }

  // Top side edit icon click
  _onEditButtonClick(event) {
    if (event.currentTarget.classList.contains('disabled')) {
      return;
    }
    const selected = this._datatableExtend.getSelectedRows();
    this._onEditRowClick(this._datatable.row(selected[0][0]));
  }

  // Direct click from row title
  _onEditRowClick(rowToEdit) {
    console.log('bbb');
    this._rowToEdit = rowToEdit; // Passed from DatatableExtend via callback from settings
    this._showModal('edit', 'Edit', 'Done');
    this._setForm();
  }

  // Edit button inside th modal click
  _editRowFromModal() {
    const data = this._rowToEdit.data();
    console.log(data);
    const formData = Object.assign(data, this._getFormData());
    this._datatable.row(this._rowToEdit).data(formData).draw();
    this._datatableExtend.unCheckAllRows();
    this._datatableExtend.controlCheckAll();
  }

  // Add button inside th modal click
  _addNewRowFromModal() {
    // document.querySelectorAll("input[type=submit]")[0].click();
    // document.querySelectorAll("#editform").submit();
    document.getElementById("editform").submit();
    const data = this._getFormData();
    // this._datatable.row.add(data).draw();
    // this._datatableExtend.unCheckAllRows();
    // this._datatableExtend.controlCheckAll();
  }

  // Delete icon click
  _onDeleteClick() {
    const selected = this._datatableExtend.getSelectedRows();
    selected.remove().draw();
    this._datatableExtend.controlCheckAll();
  }

  // + Add New or just + button from top side click
  _onAddRowClick() {

    document.querySelector('#item_name').value = '';
    document.querySelector('#item_model').value = '';
    document.querySelector('#item_price').value = '';
    document.querySelector('#item_planToUseInMonths').value = '';
    document.querySelector('#item_buyDate').value = '';
    document.querySelector('#item_endDate').value = '';


    document.querySelector('#editform').action = document.getElementById('new-item-url').dataset.link;
    this._showModal('add', 'Add New', 'Add');
  }

  // Showing modal for an objective, add or edit
  _showModal(objective, title, button) {
    this._addEditModal.show();
    this._currentState = objective;
    document.getElementById('modalTitle').innerHTML = title;
    document.getElementById('addEditConfirmButton').innerHTML = button;
  }

  _normDate(original) {
    return original ? original.substring(8,10) + '.' + original.substring(5,7) + '.' + original.substring(0,4) : '';
  }

  // Filling the modal form data
  _setForm() {
    const data = this._rowToEdit.data();
    console.log(data);
    document.querySelector('#item_name').value = data.name;
    document.querySelector('#item_model').value = data.model;
    document.querySelector('#item_price').value = data.price;
    document.querySelector('#item_planToUseInMonths').value = data.plantouse;
    document.querySelector('#item_buyDate').value = this._normDate(data.buydate);
    document.querySelector('#item_endDate').value = this._normDate(data.enddate);
    document.querySelector('#editform').action = data.id;
  }

  // Getting form values from the fields to pass to datatable
  _getFormData() {
    // const data = {};
    // data.Name = document.querySelector('#addEditModal input[name=Name]').value;
    // data.Sales = document.querySelector('#addEditModal input[name=Sales]').value;
    // data.Stock = document.querySelector('#addEditModal input[name=Stock]').value;
    // data.Category = document.querySelector('#addEditModal input[name=Category]:checked')
    //   ? document.querySelector('#addEditModal input[name=Category]:checked').value || ''
    //   : '';
    // data.Tag = document.querySelector('#addEditModal input[name=Tag]:checked')
    //   ? document.querySelector('#addEditModal input[name=Tag]:checked').value || ''
    //   : '';
    // data.Check = '';
    return data;
  }

  // Clearing modal form
  _clearModalForm() {
    document.querySelector('#addEditModal form').reset();
  }

  // Update tag from top side dropdown
  _updateTag(tag) {
    const selected = this._datatableExtend.getSelectedRows();
    const _this = this;
    selected.every(function (rowIdx, tableLoop, rowLoop) {
      const data = this.data();
      data.done = tag;
      _this._datatable.row(this).data(data).draw();
    });
    this._datatableExtend.unCheckAllRows();
    this._datatableExtend.controlCheckAll();
  }

  // Single item select callback from DatatableExtend
  _onSingleSelect() {
    document.querySelectorAll('.edit-datatable').forEach((el) => el.classList.remove('disabled'));
  }

  // Multiple item select callback from DatatableExtend
  _onMultipleSelect() {
    document.querySelectorAll('.edit-datatable').forEach((el) => el.classList.add('disabled'));
  }

  // One or more item select callback from DatatableExtend
  _onAnySelect() {
    document.querySelectorAll('.delete-datatable').forEach((el) => el.classList.remove('disabled'));
    document.querySelectorAll('.tag-datatable').forEach((el) => el.classList.remove('disabled'));
  }

  // Deselect callback from DatatableExtend
  _onNoneSelect() {
    document.querySelectorAll('.delete-datatable').forEach((el) => el.classList.add('disabled'));
    document.querySelectorAll('.tag-datatable').forEach((el) => el.classList.add('disabled'));
  }
}
