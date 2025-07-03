import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActiviteService } from '../services/activite.service';
import { PersonnelService } from '../services/personnel.service';
import { Activite } from '../models/activite.model';
import { Personnel } from '../models/personnel.model';

@Component({
  selector: 'app-activite-list',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './activite-list.component.html',
  styleUrls: ['./activite-list.component.css']
})
export class ActiviteListComponent implements OnInit {
  activites: Activite[] = [];
  personnels: Personnel[] = [];
  activiteForm: FormGroup;
  edition: boolean = false;
  activiteEnCours?: Activite;
  message: string = '';

  joursSemaine = [
    'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'
  ];

  constructor(
    private activiteService: ActiviteService,
    private personnelService: PersonnelService,
    private fb: FormBuilder
  ) {
    this.activiteForm = this.fb.group({
      personnel_id: ['', Validators.required],
      titre: ['', Validators.required],
      jour_semaine: ['', Validators.required],
      heure_debut: ['', Validators.required],
      heure_fin: ['', Validators.required]
    });
  }

  ngOnInit(): void {
    this.chargerActivites();
    this.chargerPersonnels();
  }

  chargerActivites(): void {
    this.activiteService.listerActivites().subscribe(data => {
      this.activites = data;
    });
  }

  chargerPersonnels(): void {
    this.personnelService.listerPersonnels().subscribe(data => {
      this.personnels = data;
    });
  }

  soumettre(): void {
    if (this.activiteForm.invalid) return;
    if (this.edition && this.activiteEnCours) {
      this.activiteService.modifierActivite(this.activiteEnCours.id!, this.activiteForm.value).subscribe(() => {
        this.message = 'Activité modifiée avec succès';
        this.annulerEdition();
        this.chargerActivites();
      });
    } else {
      this.activiteService.creerActivite(this.activiteForm.value).subscribe(() => {
        this.message = 'Activité ajoutée avec succès';
        this.activiteForm.reset();
        this.chargerActivites();
      });
    }
  }

  modifier(activite: Activite): void {
    this.edition = true;
    this.activiteEnCours = activite;
    this.activiteForm.patchValue(activite);
  }

  supprimer(id: number): void {
    if (confirm('Voulez-vous vraiment supprimer cette activité ?')) {
      this.activiteService.supprimerActivite(id).subscribe(() => {
        this.message = 'Activité supprimée';
        this.chargerActivites();
      });
    }
  }

  annulerEdition(): void {
    this.edition = false;
    this.activiteEnCours = undefined;
    this.activiteForm.reset();
  }

  // Regroupe les activités par jour et par tranche horaire
  activitesParJour(): { [jour: string]: Activite[] } {
    const resultat: { [jour: string]: Activite[] } = {};
    for (const jour of this.joursSemaine) {
      resultat[jour] = this.activites.filter(a => a.jour_semaine === jour);
      resultat[jour].sort((a, b) => a.heure_debut.localeCompare(b.heure_debut));
    }
    return resultat;
  }

  public getNom(personnel: Personnel | undefined): string {
    return personnel?.nom || personnel?.name || 'Nom inconnu';
  }
} 