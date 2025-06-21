<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">User Management</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="md:col-span-1">
        <user-form @user-created="fetchUsers"></user-form>
      </div>
      <div class="md:col-span-2">
        <user-list ref="userList" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import UserForm from '../components/UserForm.vue';
import UserList from '../components/UserList.vue';

const userList = ref(null);

const fetchUsers = () => {
  if (userList.value) {
    userList.value.fetchUsers();
  }
};
</script> 