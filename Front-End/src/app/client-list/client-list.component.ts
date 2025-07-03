import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { Client, ClientService } from '../services/client.service';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-client-list',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './client-list.component.html',
  styleUrls: ['./client-list.component.css']
})
export class ClientListComponent implements OnInit {
  clients: Client[] = [];
  loading = false;
  showForm = false;
  editMode = false;
  selectedClient: Client | null = null;
  clientForm: FormGroup;

  constructor(private clientService: ClientService, private fb: FormBuilder) {
    this.clientForm = this.fb.group({
      nom: ['', Validators.required],
      prenom: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      telephone: [''],
      adresse: ['']
    });
  }

  ngOnInit() {
    this.fetchClients();
  }

  fetchClients() {
    this.loading = true;
    this.clientService.getClients().subscribe({
      next: (data) => { this.clients = data; this.loading = false; },
      error: () => { this.loading = false; }
    });
  }

  openForm(client?: Client) {
    this.showForm = true;
    this.editMode = !!client;
    this.selectedClient = client || null;
    if (client) {
      this.clientForm.patchValue(client);
    } else {
      this.clientForm.reset();
    }
  }

  closeForm() {
    this.showForm = false;
    this.selectedClient = null;
    this.clientForm.reset();
  }

  submitForm() {
    if (this.clientForm.invalid) return;
    const clientData = this.clientForm.value;
    if (this.editMode && this.selectedClient) {
      this.clientService.updateClient(this.selectedClient.id!, clientData).subscribe(() => {
        this.fetchClients();
        this.closeForm();
      });
    } else {
      this.clientService.addClient(clientData).subscribe(() => {
        this.fetchClients();
        this.closeForm();
      });
    }
  }

  editClient(client: Client) {
    this.openForm(client);
  }

  deleteClient(id: number | undefined) {
    if (!id) return;
    if (confirm('Supprimer ce client ?')) {
      this.clientService.deleteClient(id).subscribe(() => this.fetchClients());
    }
  }
} 