<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head,router } from '@inertiajs/vue3';
import { reactive,ref,watch } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import TextInput from '@/Components/TextInput.vue';

const checkUpdateBtn = ref(true);
const search = ref('');

defineProps({
    todos: Object,
    errors: Object,
    links:Object
});

const form = reactive({
    content: null,
    id : null
});

function submit(){
    router.post('/todos',form);
    form.content = null;
    form.id = null;
}

function updateStatus(todo) {
    if(window.confirm('Are u sure want to update')) {
        router.put('/todos/'+todo.id+'/update');
    }
}

function deleteTodo(todo) {
    if(window.confirm('Are u sure want to delete')) {
        router.delete('/todos/'+todo.id);
        form.content = null;
        form.id = null;
    }
}

function editTodo(todo) {
    form.id = todo.id;
    form.content = todo.content;
    checkUpdateBtn.value = false;
}

function updateTodo() {
    router.post('/todos',form);
    checkUpdateBtn.value = true;
    form.content = null;
    form.id = null;
}

watch(search,value=>{
    router.get('/todos',{search:value},{
        preserveState: true,
    });
});


</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Todo List</h2>
        </template>

        <div class="h-100 w-full flex items-center justify-center bg-teal-lightest font-sans">
            <div class="bg-white rounded shadow p-6 m-4 w-full">
                <div class="mt-6">
                    <TextInput
                        id="search"
                        v-model="search"
                        type="search"
                        class="mt-1 w-half"
                        placeholder="Search by name"
                    />
                </div>
                <div>
                    <div class="mb-4">
                        <div class="flex mt-4">
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 mr-4 text-grey-darker" placeholder="Add Todo"  v-model="form.content">
                            <button v-if="checkUpdateBtn == true" class="flex-no-shrink p-2 border-2 rounded-lg text-teal border-teal text-white bg-blue-500 hover:bg-blue-700" @click="submit">Add</button>
                            <button v-else class="flex-no-shrink p-2 border-2 rounded-lg text-teal border-teal text-white bg-blue-500 hover:bg-blue-700" @click="updateTodo">Update</button>
                        </div>
                        <p v-if="errors?.content" class="text-red-500">{{ errors?.content }}</p>
                    </div>
                    <div v-if="todos.data.length">
                        <div class="flex mb-4 items-center"  v-for="todo in todos.data" >
                            <p class="w-full text-grey-darkest">{{ todo?.content }}</p>
                            <button v-if="todo?.is_done" class="flex-no-shrink p-2 ml-4 mr-2 border-2 rounded-lg border-grey" @click="updateStatus(todo)">Not Done</button>
                            <button v-else class="flex-no-shrink p-2 ml-4 mr-2 border-2 rounded-lg border-green text-white bg-green-500 hover:bg-green-700" @click="updateStatus(todo)">Done</button>
                            <button class="flex-no-shrink p-2 ml-2 border-2 rounded-lg text-red border-red text-white bg-red-500 hover:bg-red-700" @click="deleteTodo(todo)">Remove</button>
                            <button class="flex-no-shrink p-2 ml-2 border-2 rounded-lg text-red border-blue text-white bg-blue-500 hover:bg-blue-700" @click="editTodo(todo)">Edit</button>
                        </div>
                        <Pagination class="mt-6 float-right" :links="todos.links" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
