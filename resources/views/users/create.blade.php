<x-adminlte-modal id="modalCustomer" title="Employee Details" theme="purple" icon="fas fa-user" size='md'>

    <form id="addCustomerForm" name="yes" method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="form-group">
            <label for="barcode" class="text-black">Name</label>
            <div class="input-group">
                <input type="hidden" id="user_id" name="user_id">
                <input type="text" id="name" name="name" class="form-control" placeholder="enter name"
                    required>
            </div>
        </div>

        <div class="form-group">
            <label for="product_name">Registration Number</label>
            <input type="text" id="registration_no" name="registration_no" class="form-control"
                placeholder="enter registration number">
        </div>

        <div class="form-group">
            <label for="product_name">Mobile</label>
            <input type="text" min="10" max="10" id="mobile" pattern="\d+" name="mobile"
                class="form-control" placeholder="enter mobile" required>
        </div>

        <!-- Role Select -->
        <div class="form-group">
            <label for="category">Role</label>
            <div class="input-group">
                <select id="role" name="role" class="form-control" required>
                    @php
                        foreach ($roles as $value) {
                            echo "<option value='" . $value->id . "'>" . $value->name . '</option>';
                        }

                        if (empty($departments)) {
                            echo "<option value='0'>No Department Found</option>";
                        }

                    @endphp.
                </select>
            </div>
        </div>

        <!-- Role Select -->
        <div class="form-group">
            <label for="category">Department</label>
            <div class="input-group">
                <select id="department_id" name="department_id" class="form-control">
                    @php
                        foreach ($departments as $value) {
                            echo "<option value='" . $value->id . "'>" . $value->name . '</option>';
                        }

                        if (empty($departments)) {
                            echo "<option value='0'>No Department Found</option>";
                        }

                    @endphp
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="stock">Email</label>
            <input type="email" id="email" name="email" placeholder="enter email" class="form-control">
        </div>

        <!-- Submit Button -->
        <div class="form-group">
            <button id="submitButton" onclick="submitProductForm()" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Employee
            </button>
        </div>
    </form>
    <x-slot name="footerSlot" :null="true">

        <!-- This will effectively remove the footer section  -->

    </x-slot>
</x-adminlte-modal>
