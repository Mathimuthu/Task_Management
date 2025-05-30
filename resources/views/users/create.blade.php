<x-adminlte-modal id="modalCustomer" title="Employee Details" theme="purple" icon="fas fa-user" size='md'>

    <form id="addCustomerForm" name="yes" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="barcode" class="text-black">Name <span style="color: red;">*</span> </label>
            <div class="input-group">
                <input type="hidden" id="user_id" name="user_id">
                <input type="text" id="name" name="name" class="form-control" placeholder="Enter the Name" 
                    pattern="[A-Za-z\s]+" title="Only alphabetic characters are allowed" required>
            </div>
        </div>

        <div class="form-group">
            <label for="product_name">Registration Number <span style="color: red;">*</span> </label>
            <input type="text" id="registration_no" name="registration_no" class="form-control"
                placeholder="Enter the Registration number" required>
        </div>

        <div class="form-group">
            <label for="product_name">Mobile <span style="color: red;">*</span> </label>
            <input type="text" min="10" max="10" id="mobile" pattern="\d+" name="mobile"
                class="form-control" placeholder="Enter the Mobile Number" required>
        </div>

        <!-- Role Select -->
        <div class="form-group">
            <label for="category">Role <span style="color: red;">*</span> </label>
            <div class="input-group">
                <select id="role" name="role" class="form-control" required>
                    @php
                        foreach ($roles as $value) {
                            echo "<option value='" . $value->id . "'>" . $value->name . '</option>';
                        }

                        if (empty($roles)) {
                            echo "<option value='0'>No Roles Found</option>";
                        }

                    @endphp.
                </select>
            </div>
        </div>

        <!-- Department Select -->
        <div class="form-group">
            <label for="category">Department <span style="color: red;">*</span> </label>
            <div class="input-group">
                <select id="department_id" name="department_id" class="form-control" required>
                    <option>Select the Department</option>
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
            <label for="stock">Email <span style="color: red;">*</span> </label>
            <input type="email" id="email" name="email" placeholder="Enter Email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password <span style="color: red;">*</span> </label>
            <div class="input-group">
                <input type="password" id="password" name="password" placeholder="Enter Password" class="form-control" required>
                <div class="input-group-append showeye">
                    <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group changepassword">
            <input type="checkbox" id="changePasswordCheckbox"> Change Password
        </div>
        <div class="form-group">
            <label for="address">Address <span style="color: red;">*</span> </label>
            <input type="text" id="address" name="address" placeholder="Enter Address" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="dob">DOB <span style="color: red;">*</span> </label>
            <input type="date" id="dob" name="dob" class="form-control" placeholder="Enter Date of Birth" 
            max="{{ date('Y-m-d') }}" required>
        </div>
        <div class="form-group">
            <label for="blood_group">Blood Group</label>
            <input type="text" id="blood_group" name="blood_group" placeholder="Enter Blood Group" class="form-control">
        </div>
        <div class="form-group">
            <label for="stock">Upload Photo</label>
            <img id="photoPreview" src="" alt="Employee Photo" class="ml-4" style="max-width: 80px; display: none;"><br>
            <input type="file" id="photo" name="photo" placeholder="Upload Photo" class="form-control">
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
