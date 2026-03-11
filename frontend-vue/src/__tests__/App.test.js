import { mount } from '@vue/test-utils'
import { nextTick } from 'vue'
import App from '../App.vue'

describe('App', () => {
  let wrapper
  const mockTasks = [
    { id: 1, title: 'Task 1', status: 'todo', priority: 2, project_name: 'Website', project_total_tasks: 5, created_at: '2026-03-10', assigned_user_name: 'Alice' },
    { id: 2, title: 'Task 2', status: 'in_progress', priority: 3, project_name: 'Website', project_total_tasks: 5, created_at: '2026-03-09', assigned_user_name: 'Bob' },
    { id: 3, title: 'Task 3', status: 'done', priority: 1, project_name: 'Internal', project_total_tasks: 3, created_at: '2026-03-08', assigned_user_name: 'Charlie' },
  ]

  beforeEach(async () => {
    wrapper = mount(App)
    
    wrapper.vm.tasks = mockTasks
    await nextTick()
  })

  it('renders all tasks', () => {
    const text = wrapper.text()
    expect(text).toContain('Task 1')
    expect(text).toContain('Task 2')
    expect(text).toContain('Task 3')
  })

  it('filters tasks by status', async () => {
    
    wrapper.vm.filterStatus = 'in_progress'
    await nextTick()
    const text = wrapper.text()
    expect(text).toContain('Task 2')
    expect(text).not.toContain('Task 1')
    expect(text).not.toContain('Task 3')
  })

  it('sorts tasks by priority descending', async () => {
    wrapper.vm.sortBy = 'priority'
    await nextTick()
    const taskTitles = wrapper.findAll('.task-title').map(el => el.text())
    expect(taskTitles).toEqual(['Task 2', 'Task 1', 'Task 3'])
  })
})
