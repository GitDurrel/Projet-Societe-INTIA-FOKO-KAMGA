import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { Assurance, AssuranceService } from '../services/assurance.service';
import { ClientService } from '../services/client.service';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-assurance-list',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './assurance-list.component.html',
  styleUrls: ['./assurance-list.component.css']
})
export class AssuranceListComponent implements OnInit {
  assurances: Assurance[] = [];
  loading = false;
  showForm = false;
  editMode = false;
  selectedAssurance: Assurance | null = null;
  assuranceForm: FormGroup;
  errorMessage: string = '';

  constructor(
    private assuranceService: AssuranceService,
    private clientService: ClientService,
    private fb: FormBuilder
  ) {
    this.assuranceForm = this.fb.group({
      type: ['', Validators.required],
      numero_police: ['', Validators.required],
      date_debut: ['', Validators.required],
      date_fin: ['', Validators.required],
      montant: ['', [Validators.required, Validators.min(0)]],
      client_id: ['', Validators.required]
    });
  }

  ngOnInit() {
    this.fetchAssurances();
  }

  fetchAssurances() {
    this.loading = true;
    this.assuranceService.getAssurances().subscribe({
      next: (data) => { this.assurances = data; this.loading = false; },
      error: () => { this.loading = false; }
    });
  }

  openForm(assurance?: Assurance) {
    this.showForm = true;
    this.editMode = !!assurance;
    this.selectedAssurance = assurance || null;
    this.errorMessage = '';
    if (assurance) {
      this.assuranceForm.patchValue(assurance);
    } else {
      this.assuranceForm.reset();
    }
  }

  closeForm() {
    this.showForm = false;
    this.selectedAssurance = null;
    this.assuranceForm.reset();
    this.errorMessage = '';
  }

  submitForm() {
    if (this.assuranceForm.invalid) return;
    const assuranceData = this.assuranceForm.value;
    this.errorMessage = '';
    // Vérification de l'existence du client
    this.clientService.getClient(Number(assuranceData.client_id)).subscribe({
      next: () => {
        if (this.editMode && this.selectedAssurance) {
          this.assuranceService.updateAssurance(this.selectedAssurance.id!, assuranceData).subscribe(() => {
            this.fetchAssurances();
            this.closeForm();
          });
        } else {
          this.assuranceService.addAssurance(assuranceData).subscribe(() => {
            this.fetchAssurances();
            this.closeForm();
          });
        }
      },
      error: () => {
        this.errorMessage = "Aucun client avec cet ID n'existe. Veuillez vérifier l'identifiant du client.";
      }
    });
  }

  editAssurance(assurance: Assurance) {
    this.openForm(assurance);
  }

  deleteAssurance(id: number | undefined) {
    if (!id) return;
    if (confirm('Supprimer cette assurance ?')) {
      this.assuranceService.deleteAssurance(id).subscribe(() => this.fetchAssurances());
    }
  }
} 