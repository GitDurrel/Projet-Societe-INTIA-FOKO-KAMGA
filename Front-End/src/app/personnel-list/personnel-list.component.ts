import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { PersonnelService } from '../services/personnel.service';
import { Personnel } from '../models/personnel.model';

@Component({
  selector: 'app-personnel-list',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './personnel-list.component.html',
  styleUrls: ['./personnel-list.component.css']
})
export class PersonnelListComponent implements OnInit {
  personnels: Personnel[] = [];
  personnelForm: FormGroup;
  edition: boolean = false;
  personnelEnCours?: Personnel;
  message: string = '';

  constructor(
    private personnelService: PersonnelService,
    private fb: FormBuilder
  ) {
    this.personnelForm = this.fb.group({
      nom: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      mot_de_passe: ['', Validators.minLength(6)]
    });
  }

  ngOnInit(): void {
    this.chargerPersonnels();
  }

  chargerPersonnels(): void {
    this.personnelService.listerPersonnels().subscribe(data => {
      this.personnels = data;
    });
  }

  soumettre(): void {
    if (this.personnelForm.invalid) return;
    if (this.edition && this.personnelEnCours) {
      const donnees = { ...this.personnelForm.value };
      if (!donnees.mot_de_passe) delete donnees.mot_de_passe;
      this.personnelService.modifierPersonnel(this.personnelEnCours.id!, donnees).subscribe(() => {
        this.message = 'Personnel modifié avec succès';
        this.annulerEdition();
        this.chargerPersonnels();
      });
    } else {
      this.personnelService.creerPersonnel(this.personnelForm.value).subscribe(() => {
        this.message = 'Personnel ajouté avec succès';
        this.personnelForm.reset();
        this.chargerPersonnels();
      });
    }
  }

  modifier(personnel: Personnel): void {
    this.edition = true;
    this.personnelEnCours = personnel;
    this.personnelForm.patchValue({
      nom: personnel.nom || personnel.name,
      email: personnel.email,
      mot_de_passe: ''
    });
  }

  supprimer(id: number): void {
    if (confirm('Voulez-vous vraiment supprimer ce membre du personnel ?')) {
      this.personnelService.supprimerPersonnel(id).subscribe(() => {
        this.message = 'Personnel supprimé';
        this.chargerPersonnels();
      });
    }
  }

  annulerEdition(): void {
    this.edition = false;
    this.personnelEnCours = undefined;
    this.personnelForm.reset();
  }

  public getNom(personnel: Personnel | undefined): string {
    return personnel?.nom || personnel?.name || 'Nom inconnu';
  }
} 