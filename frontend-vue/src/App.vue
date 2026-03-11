<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import dayjs from "dayjs";


const tasks = ref([]);  
const filterStatus = ref(""); 
const sortBy = ref(""); 


const statuses = [
  { label: "All", value: "" },
  { label: "Todo", value: "todo" },
  { label: "In Progress", value: "in_progress" },
  { label: "Done", value: "done" },
];

// Sort options
const sortOptions = [
  { label: "All", value: "" }, 
  { label: "Priority", value: "priority" },
  { label: "Overdue", value: "overdue" },
  { label: "Project Importance", value: "importance" },
];

// ----------------- FETCH DATA -----------------
const fetchTasks = async () => {
  try {
    const res = await axios.get("http://127.0.0.1:8000/api/dashboard-tasks");
    tasks.value = res.data; 
  } catch (err) {
    console.error("Error fetching tasks:", err);
  }
};

onMounted(fetchTasks);

// ----------------- HELPERS -----------------
const isOverdue = (task) => {
  if (!task.created_at || task.status !== "todo") return false;
  return dayjs().diff(dayjs(task.created_at), "day") >= 3;
};

// Compute how many days ago task was created
const daysAgo = (task) => {
  if (!task.created_at) return "-";
  const diff = dayjs().diff(dayjs(task.created_at), "day");
  return diff === 0 ? "Today" : `${diff} day${diff > 1 ? "s" : ""} ago`;
};

// ----------------- COMPUTED TASK LIST -----------------
const filteredTasks = computed(() => {
  let filtered = tasks.value;

  // Filter by status
  if (filterStatus.value) {
    filtered = filtered.filter(t => t.status === filterStatus.value);
  }

  // Sort tasks
  filtered.sort((a, b) => {
    if (sortBy.value === 'priority') return b.priority - a.priority;
    if (sortBy.value === 'overdue') return (isOverdue(b) ? 1 : 0) - (isOverdue(a) ? 1 : 0);
    if (sortBy.value === 'importance') return (b.project_total_tasks || 0) - (a.project_total_tasks || 0);

   
    const aOver = isOverdue(a) ? 1 : 0;
    const bOver = isOverdue(b) ? 1 : 0;
    if (aOver !== bOver) return bOver - aOver;
    if (a.priority !== b.priority) return b.priority - a.priority;
    return (b.project_total_tasks || 0) - (a.project_total_tasks || 0);
  });

  return filtered;
});
</script>

<template>
<div class="container py-4">
  <h1 class="mb-4">Task Dashboard</h1>

  <!-- Status Filter -->
  <div class="mb-3 d-flex flex-wrap gap-2">
    <button 
      v-for="status in statuses" 
      :key="status.value" 
      @click="filterStatus = status.value"
      :class="['btn', filterStatus === status.value ? 'btn-primary' : 'btn-outline-secondary']"
    >
      {{ status.label }}
    </button>
  </div>

  <!-- Sort Dropdown -->
  <div class="mb-4 d-flex align-items-center gap-2">
    <label>Sort:</label>
    <select v-model="sortBy" class="form-select w-auto">
      <option v-for="option in sortOptions" :key="option.value" :value="option.value">
        {{ option.label }}
      </option>
    </select>
  </div>

  <!-- Task List -->
  <div class="task-list">
    <div v-for="task in filteredTasks" :key="task.id" class="task-card mb-3 shadow-sm">
      
      <!-- Header -->
      <div class="task-header d-flex justify-content-between align-items-center">
        <span class="project-badge">{{ task.project_name }}</span>
        <span 
          class="status-badge"
          :class="{
            'done': task.status === 'done',
            'in-progress': task.status === 'in_progress',
            'overdue': isOverdue(task),
            'todo': task.status === 'todo' && !isOverdue(task)
          }"
        >
          {{ task.status.replace('_', ' ') }}
        </span>
      </div>

      <!-- Title & Description -->
      <h5 class="task-title mt-2">{{ task.title }}</h5>
      <p class="task-desc">{{ task.description }}</p>

      <!-- Footer -->
      <div class="d-flex justify-content-between align-items-center mt-3">
        <small class="text-muted">
          Assigned: {{ task.assigned_user_name || 'Unassigned' }} |
          Created: {{ daysAgo(task) }}
        </small>
        <span class="priority-badge">Priority: {{ task.priority }}</span>
      </div>

    </div>
  </div>
</div>
</template>

<style scoped>
.container {
   font-family: 'Arial', sans-serif;
   }


.btn-outline-secondary {
   transition: 0.3s;
   }
.btn-outline-secondary:hover { 
  background-color: #ddd; 
}


.task-card { 
  background-color: #fff; 
  border-radius: 12px;
   padding: 20px;
    transition: transform 0.2s, box-shadow 0.2s;
   }
.task-card:hover { 
  transform: translateY(-3px);
   box-shadow: 0 8px 20px rgba(0,0,0,0.15);
   }


.task-header {
   display: flex; 
  justify-content: space-between; 
  align-items: center; 
}

.project-badge { 
  background-color: #007BFF; 
  color: white; 
  padding: 4px 12px; 
  border-radius: 12px;
   font-size: 0.8em;
   }

.status-badge {
   padding: 4px 10px;
    border-radius: 12px; 
    color: white; 
    font-weight: bold; 
    font-size: 0.8em; 
    text-transform: capitalize; 
  }
.status-badge.done {
   background-color: #28a745; 
  }
.status-badge.in-progress {
   background-color: #ffc107;
   }
.status-badge.todo { 
  background-color: #17a2b8; 
}
.status-badge.overdue {
   background-color: #dc3545; 
  }


.task-title {
   font-weight: 600;
    margin: 0; 
  }
.task-desc { 
  color: #555;
   font-size: 0.9em;
    margin: 5px 0 0;
   }

.task-card { 
  background-color: #f8f9fa; 
  border-radius: 12px; 
  padding: 20px; 
  transition: transform 0.2s, box-shadow 0.2s; 
}
.task-card:hover { 
  transform: translateY(-3px); 
  box-shadow: 0 8px 20px rgba(0,0,0,0.15); 
}


.priority-badge { background-color: #ff5722; 
color: white;
 padding: 4px 12px; 
 border-radius: 12px; 
 font-size: 0.8em; 
 font-weight: bold; 
 }
</style>
