<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head,useForm,router } from '@inertiajs/vue3';
import { ref,watch } from 'vue';
import Pagination from '@/Components/Pagination.vue';

const showModal = ref(false);
const editShowModal = ref(false);
const search = ref('');
const profileImage = ref('');
const baseUrl = window.location.origin;
const selectHobbies = [];

defineProps({
    users:Object,
    links:Object
});

const form = useForm({
    name: '',
    email: '',
    mobile: '',
    password: '',
    image: '',
    gender: '',
    dob: '',
    hobbies: '',
});

const genders = [
    {
        name: 'M',
        description: 'Male'
    },
    {
        name: 'F',
        description: 'Female'
    },
    {
        name: 'O',
        description: 'Other'
    },
];

const hobbiesList = [
    {
        id: 1,
        name:'Cricket',
        description: 'cricket'
    },
    {
        id: 2,
        name:'Football',
        description: 'football'
    },
    {
        id: 2,
        name:'Vollyball',
        description: 'vollyball'
    },
];

const openModal = () => {
    showModal.value = true;
}

const closeModal = () => {
    showModal.value = false;
    editShowModal.value=false;
    form.reset();
    form.clearErrors();
}

const saveData = () => {
    form.post(route('users.store'),{
        onSuccess: () => {
            form.reset();
            showModal.value = false;
            form.clearErrors();
            alert('Data added successfully');
        },
        forceFormData: true,
    });
}

const editModal = (item) => {
    showModal.value = true;
    editShowModal.value = true;
    form.id = item.id;
    form.name = item.name;
    form.email = item.email;
    form.mobile = item.mobile;
    form.image = '';
    form.gender = item.gender;
    form.dob = item.dob;
    form.hobbies = item.hobbies;
    form.password = '';
    profileImage.value = item.image;
}

const updateData = () => {
    // console.log(form); return false;
    form.post(route('users.updateData',[form]),{
        onSuccess: () => {
            form.reset();
            showModal.value = false;
            editShowModal.value=false;
            form.clearErrors();
            alert('Data is updated successfully');
        },
        forceFormData: true,
    });
}

const deleteUser = (user) => {
    if(confirm("Are you sure to Delete this user?"))
    {
        router.delete(route('users.destroy',[user]),{
            onSuccess: () => {
            alert('Data deleted successfully');
            showModal.value = true;
        },
        });
    }
}

const updateStatus = (user) => {
   router.get(route('users.status',[user]),{
        onSuccess: () => {
            alert('User Status Changed Successfully');
        }
   });
}

watch(search,value=>{
    router.get('/users',{search:value},{
        preserveState: true,
    });
});

const selectedHobbies = (options) => {
    let index = selectHobbies.findIndex(a => a === options)
    if (index >= 0)
        selectHobbies.splice(index, 1);
    else
        selectHobbies.push(options);
}
</script>

<template>
    <Head title="User Listing" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">User List</h2>
        </template>

        <div class="h-100 w-full flex items-center justify-center bg-teal-lightest font-sans">
            <div class="bg-white rounded shadow p-6 m-4 w-full">
                <div v-if="$page.props.flash.message" id="alert-3" class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div class="ml-3 text-sm font-medium">
                        {{ $page.props.flash.message }}
                    </div>
                    <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-3" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>
                <div v-if="$page.props.flash.error" id="alert-2" class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div class="ml-3 text-sm font-medium">
                        {{ $page.props.flash.error }}
                    </div>
                    <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-2" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>
                <a href="/users/pdf" class="flex-no-shrink p-2 border-2 rounded-sm text-teal border-teal text-white bg-red-700 float-right my-2" >Pdf</a>
                <button class="flex-no-shrink p-2 border-2 rounded-sm text-teal border-teal text-white bg-blue-700 float-right my-2" @click="openModal">Add New</button>
                <TextInput
                    id="search"
                    v-model="search"
                    type="search"
                    class="mt-1 w-half float-right
                    rounded-sm mt-2 mr-2"
                    placeholder="Search by name"
                />
                <table class="border w-full text-center">
                    <thead>
                        <tr>
                            <th class="border border-slate-300">#</th>
                            <th class="border border-slate-300">Image</th>
                            <th class="border border-slate-300">Name</th>
                            <th class="border border-slate-300">Email</th>
                            <th class="border border-slate-300">Mobile</th>
                            <th class="border border-slate-300">Gender</th>
                            <th class="border border-slate-300">Active/Inactive</th>
                            <th class="border border-slate-300">Status</th>
                            <th class="border border-slate-300">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(value, key, index) in users.data">
                            <td class="border border-slate-300">{{ key+1 }}</td>
                            <td class="border border-slate-300">
                                <div v-if="value?.image">
                                    <img :src="baseUrl+/storage/+value?.image" class="inline-block h-12 w-12 rounded-full ring-2 ring-white">
                                </div>
                            </td>
                            <td class="border border-slate-300">{{ value?.name }}</td>
                            <td class="border border-slate-300">{{ value?.email }}</td>
                            <td class="border border-slate-300">{{ value?.mobile }}</td>
                            <td class="border border-slate-300">{{ value?.gender }}</td>
                            <td class="border border-slate-300">
                                <label class="relative inline-flex items-center mr-5 cursor-pointer">
                                    <input type="checkbox" value="" class="sr-only peer" :checked="value?.is_active === 1" @change="updateStatus(value)">
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700
                                        peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus:ring-green-800
                                        peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute
                                        after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full
                                        after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600">
                                    </div>
                                </label>
                            </td>
                            <td class="border border-slate-300">
                                <span v-if="value?.is_active === 1" class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300 cursor-pointer">Active</span>
                                <span v-else class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300 cursor-pointer">In Active</span>
                            </td>

                            <td class="border border-slate-300">
                                <button class="flex-no-shrink p-2 border-2 rounded-sm text-teal border-teal text-white bg-blue-700 my-2" @click="editModal(value)">Edit</button>
                                <button class="flex-no-shrink p-2 border-2 rounded-sm text-teal border-teal text-white bg-red-700 my-2" @click="deleteUser(value)">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="users.data.length">
                    <Pagination class="mt-6 float-right" :links="users.links" />
                </div>
            </div>
            <!-- Main modal -->
            <div v-if="showModal" class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
                <div class="relative max-h-full" style="width: 60%;">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ editShowModal ? 'Edit User' : 'Add New User' }}</h3>
                            <button @click="closeModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="defaultModal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="relative p-6 flex-auto">
                            <div class="mt-6">
                                <InputLabel for="name" value="Name" />
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 w-full"
                                    placeholder="Name"
                                />
                                <InputError :message="form.errors.name" class="mt-2" />
                            </div>
                            <div class="mt-6">
                                <InputLabel for="email" value="Email" />
                                <TextInput
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="mt-1 w-full"
                                    placeholder="Email"
                                />
                                <InputError :message="form.errors.email" class="mt-2" />
                            </div>
                            <div class="mt-6">
                                <InputLabel for="mobile" value="Mobile" />
                                <TextInput
                                    id="mobile"
                                    v-model="form.mobile"
                                    type="number"
                                    class="mt-1 w-full"
                                    placeholder="Mobile"
                                />
                                <InputError :message="form.errors.mobile" class="mt-2" />
                            </div>
                            <div class="mt-6">
                                <InputLabel for="password" value="Password" />
                                <TextInput
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    class="mt-1 w-full"
                                    placeholder="Password"
                                />
                                <InputError :message="form.errors.password" class="mt-2" />
                            </div>
                            <div class="mt-6">
                                <InputLabel for="image" value="Image" />
                                <TextInput type="file" @input="form.image = $event.target.files[0]" />
                                <img v-if="profileImage" :src="baseUrl+/storage/+profileImage" class="inline-block h-12 w-12 rounded-full ring-2 ring-white">
                            </div>
                            <div class="mt-6">
                                <label class="mr-2" v-for="genderData in genders" >
                                <TextInput
                                    type="radio"
                                    v-model="form.gender"
                                    class="mt-1 mr-2"
                                    :name="form.gender"
                                    :value="genderData.description"
                                    :checked ="genderData.description == form.gender"
                                />
                                {{ genderData.name }}
                                </label>
                                <InputError :message="form.errors.gender" class="mt-2" />
                            </div>
                            <div class="mt-6">
                                <InputLabel for="dob" value="Date of Birth" />
                                <TextInput
                                    id="dob"
                                    v-model="form.dob"
                                    type="date"
                                    class="mt-1 w-full"
                                    placeholder="Date of birth"
                                />
                                <InputError :message="form.errors.dob" class="mt-2" />
                            </div>
                            <!-- <div class="mt-6">
                                <label class="mr-2" v-for="hobbyData in hobbiesList" >
                                <TextInput
                                    id ="hobbyData.id"
                                    type="checkbox"
                                    class="mt-1 mr-2"
                                    :value="hobbyData.description"
                                    v-model="form.hobbies"
                                    @click="selectedHobbies(hobbyData.description)"
                                />
                                {{ hobbyData.name }}
                                </label>
                                <InputError :message="form.errors.hobbies" class="mt-2" />
                            </div> -->
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button v-if="!editShowModal" @click="saveData" data-modal-hide="defaultModal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                            <button v-else @click="updateData" data-modal-hide="defaultModal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update</button>
                            <button @click="closeModal" data-modal-hide="defaultModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
