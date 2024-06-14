<template>
  <div class="wrapper">
    <Slider/>
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
      <Header/>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Tasks</h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 400px;">
                  <table class="table table-head-fixed text-nowrap">
                    <thead>
                    <tr>
                      <th>ID</th>
                      <th>Title</th>
                      <th>Description</th>
                      <th>Execution Date</th>
                      <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="task in tasks" :key="task.id">
                      <td>{{ task.id }}</td>
                      <router-link :to="{ name: 'TaskDetail', params: { taskId: task.id } }">
                        <td>{{ task.title }}</td>
                      </router-link>
                      <td>{{ task.description }}</td>
                      <td>{{ task.execution_date }}</td>
                      <td>

                        <router-link :to="{ name: 'TaskDetail', params: { taskId: task.id } }">
                          <button class="btn btn-info btn-sm float-left mr-1">
                            <i class="fas fa-pencil-alt"></i>
                          </button>
                        </router-link>
                        <button @click="confirmDelete(task.id)" class="btn btn-danger btn-sm">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <Footer/>
  </div>
</template>

<script>
import axios from 'axios';
import Slider from '@/components/Slider.vue';
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";

export default {
  name: 'App-Home',
  components: {
    Slider,
    Header,
    Footer
  },
  data() {
    return {
      tasks: []
    };
  },
  created() {
    this.fetchTasks();
  },
  methods: {
    fetchTasks() {
      axios.get(`${process.env.VUE_APP_BASE_URL}/tasks/list`)
          .then(response => {
            if (response.data.success) {
              this.tasks = response.data.data;
            } else {
              console.error('Error fetching tasks:', response.data.message);
            }
          })
          .catch(error => {
            console.error('Error fetching tasks:', error);
          });
    },
    confirmDelete(taskId) {
      if (confirm('Confirm Delete')) {
        this.deleteTask(taskId);
      }
    },
    deleteTask(taskId) {
      axios.delete(`${process.env.VUE_APP_BASE_URL}/tasks/delete`, {
        data: {
          id: taskId
        }
      }).then(response => {
            console.log(response);
            if (response.data.success) {
              this.fetchTasks();
            } else {
              console.error('Error deleting task:', response.data.message);
            }
          })
          .catch(error => {
            console.error('Error deleting task:', error);
          });
    }
  }
};
</script>

