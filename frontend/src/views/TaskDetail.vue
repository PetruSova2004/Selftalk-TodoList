<template>
  <div class="wrapper">
    <Slider/>
    <div class="content-wrapper">
      <section class="content-header">
        <Header/>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row" style="margin-left: 20%">
            <div class="col-md-6">
              <div class="card card-danger">
                <div class="card-header">
                  <h3 class="card-title">Task</h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label>Title</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-save"></i></span>
                      </div>
                      <input v-model="task.title" type="text" class="form-control">
                    </div>
                    <small v-if="!task.title" class="text-danger">Title is required</small>
                  </div>

                  <div class="form-group">
                    <label>Description</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                      </div>
                      <input v-model="task.description" type="text" class="form-control">
                    </div>
                    <small v-if="!task.description" class="text-danger">Description is required</small>
                  </div>

                  <div class="form-group">
                    <label>Execution Date</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                      </div>
                      <input v-model="task.execution_date" type="date" class="form-control">
                    </div>
                    <small v-if="!task.execution_date" class="text-danger">Execution Date is required</small>
                  </div>
                </div>
                <button @click="saveTask" class="btn btn-block btn-success">Save</button>
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
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";
import Slider from "@/components/Slider.vue";

export default {
  name: 'TaskDetail',
  components: {Slider, Footer, Header},
  data() {
    return {
      task: {
        id: null,
        title: '',
        description: '',
        execution_date: ''
      },
      fetched: false,
    };
  },
  created() {
    this.fetchTask();
  },
  methods: {
    fetchTask() {
      const taskId = this.$route.params.taskId;
      axios.get(`${process.env.VUE_APP_BASE_URL}/tasks/show?id=${taskId}`)
          .then(response => {
            if (response.data.success) {
              this.task = response.data.data;
              console.log(this.task);
            } else {
              console.error('Failed to fetch task:', response.data.message);
            }
            this.fetched = true;
          })
          .catch(error => {
            console.error('Error fetching task:', error);
            this.fetched = true; // Ensure fetched is set to true even if there's an error
          });
    },
    validateInput(input) {
      const invalidChars = /[`~!#$%^&*()+={}[\]|\\:;"'<>,?]/;
      return !invalidChars.test(input);
    },
    saveTask() {
      if (!this.task.title || !this.task.description || !this.task.execution_date) {
        alert('Please fill in all required fields.');
        return;
      }

      if (!this.validateInput(this.task.title) || !this.validateInput(this.task.description)) {
        alert('Input contains invalid characters.');
        return;
      }

      const postData = {
        id: this.task.id,
        title: this.task.title,
        description: this.task.description,
        execution_date: this.task.execution_date
      };

      // Send PUT request to the server
      axios.put(`${process.env.VUE_APP_BASE_URL}/tasks/edit`, postData)
          .then(response => {
            console.log(response);
            if (response.data.success) {
              alert("Task was updated successfully");
              this.$router.push({name: 'Home'});
            } else {
              console.log(response.data);
              if (response.data.error) {
                let errorMessage;
                if (Array.isArray(response.data.error) && response.data.error.length > 0) {
                  const firstError = response.data.error[0];
                  errorMessage = typeof firstError === 'string' ? firstError : firstError[Object.keys(firstError)[0]];
                } else if (typeof response.data.error === 'object') {
                  // If error is a single object
                  errorMessage = response.data.error[Object.keys(response.data.error)[0]];
                } else {
                  // If error is a single string
                  errorMessage = response.data.error;
                }

                alert(errorMessage);
              } else {
                alert("Something went wrong, please try again later");
              }
            }
          })
          .catch(error => {
            console.error('Error updating task:', error);
          });
    }
  }
};
</script>
