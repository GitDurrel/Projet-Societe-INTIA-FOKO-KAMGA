import { Routes } from '@angular/router';

export const routes: Routes = [
  { path: 'clients', loadComponent: () => import('./client-list/client-list.component').then(m => m.ClientListComponent) },
  { path: 'assurances', loadComponent: () => import('./assurance-list/assurance-list.component').then(m => m.AssuranceListComponent) },
  { path: '', redirectTo: '/clients', pathMatch: 'full' },
  { path: '**', redirectTo: '/clients' }
];


