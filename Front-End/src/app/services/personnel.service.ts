import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Personnel } from '../models/personnel.model';

@Injectable({ providedIn: 'root' })
export class PersonnelService {
  private apiUrl = 'http://localhost:8000/api/personnels';

  constructor(private http: HttpClient) {}

  listerPersonnels(): Observable<Personnel[]> {
    return this.http.get<Personnel[]>(this.apiUrl);
  }

  obtenirPersonnel(id: number): Observable<Personnel> {
    return this.http.get<Personnel>(`${this.apiUrl}/${id}`);
  }

  creerPersonnel(personnel: Personnel): Observable<Personnel> {
    return this.http.post<Personnel>(this.apiUrl, personnel);
  }

  modifierPersonnel(id: number, personnel: Partial<Personnel>): Observable<Personnel> {
    return this.http.put<Personnel>(`${this.apiUrl}/${id}`, personnel);
  }

  supprimerPersonnel(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`);
  }
} 