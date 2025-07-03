import { Routes } from '@angular/router';

export const routes: Routes = [
  { path: 'clients', loadComponent: () => import('./client-list/client-list.component').then(m => m.ClientListComponent) },
  { path: 'assurances', loadComponent: () => import('./assurance-list/assurance-list.component').then(m => m.AssuranceListComponent) },
  { path: 'personnels', loadComponent: () => import('./personnel-list/personnel-list.component').then(m => m.PersonnelListComponent) },
  { path: '', loadComponent: () => import('./activite-list/activite-list.component').then(m => m.ActiviteListComponent) },
  { path: '**', redirectTo: '' }
];


