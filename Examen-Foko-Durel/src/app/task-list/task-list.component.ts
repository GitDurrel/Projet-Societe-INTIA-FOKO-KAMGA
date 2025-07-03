import { Component, OnInit } from '@angular/core';
import { TaskService } from '../services/task.service';
import { Task } from '../services/task.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-task-list',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './task-list.component.html',
  styleUrls: ['./task-list.component.css']
})
export class TaskListComponent implements OnInit {
  tasks: Task[] = [];
  loading = true;
  newTask: Partial<Task> = {
    titre: '',
    description: '',
    date_echeance: ''
  };

  constructor(private taskService: TaskService) {}

  ngOnInit(): void {
    this.loadTasks();
  }
  loadTasks() {
    this.loading = true;
    this.taskService.getTasks().subscribe({
      next: (data) => {
        console.log('Tâches reçues:', data);
        this.tasks = data;
        this.loading = false;
      },
      error: (err) => {
        console.error('Erreur API:', err);
        this.loading = false;
      }
    });
  }

  markAsRead(task: Task) {
    this.taskService.markAsRead(task.id).subscribe({
      next: () => {
        task.statut = 'terminee';
      },
      error: () => {
        // Gère l'erreur ici si besoin
      }
    });
  }

  addTask() {
    this.taskService.addTask(this.newTask).subscribe({
      next: (task) => {
        this.tasks.push(task);
        this.newTask = { titre: '', description: '', date_echeance: '' };
      },
      error: (err) => {
        // Gère l'erreur ici
      }
    });
  }

  deleteTask(id: number) {
    this.taskService.deleteTask(id).subscribe({
      next: () => {
        this.tasks = this.tasks.filter(t => t.id !== id);
      },
      error: (err) => {
        // Gère l'erreur ici
      }
    });
  }
}
