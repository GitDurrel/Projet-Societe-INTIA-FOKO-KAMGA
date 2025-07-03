import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Activite } from '../models/activite.model';

@Injectable({ providedIn: 'root' })
export class ActiviteService {
  private apiUrl = 'http://localhost:8000/api/activites';

  constructor(private http: HttpClient) {}

  listerActivites(): Observable<Activite[]> {
    return this.http.get<Activite[]>(this.apiUrl);
  }

  obtenirActivite(id: number): Observable<Activite> {
    return this.http.get<Activite>(`${this.apiUrl}/${id}`);
  }

  creerActivite(activite: Activite): Observable<Activite> {
    return this.http.post<Activite>(this.apiUrl, activite);
  }

  modifierActivite(id: number, activite: Partial<Activite>): Observable<Activite> {
    return this.http.put<Activite>(`${this.apiUrl}/${id}`, activite);
  }

  supprimerActivite(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`);
  }
} 