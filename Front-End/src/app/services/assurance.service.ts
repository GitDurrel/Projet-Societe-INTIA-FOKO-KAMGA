import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface Assurance {
  id?: number;
  type: string;
  numero_police: string;
  date_debut: string;
  date_fin: string;
  montant: number;
  client_id: number;
}

@Injectable({ providedIn: 'root' })
export class AssuranceService {
  private apiUrl = 'http://localhost:8000/api/assurances';

  constructor(private http: HttpClient) {}

  getAssurances(): Observable<Assurance[]> {
    return this.http.get<Assurance[]>(this.apiUrl);
  }

  getAssurance(id: number): Observable<Assurance> {
    return this.http.get<Assurance>(`${this.apiUrl}/${id}`);
  }

  addAssurance(assurance: Assurance): Observable<Assurance> {
    return this.http.post<Assurance>(this.apiUrl, assurance);
  }

  updateAssurance(id: number, assurance: Assurance): Observable<Assurance> {
    return this.http.put<Assurance>(`${this.apiUrl}/${id}`, assurance);
  }

  deleteAssurance(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`);
  }
} 