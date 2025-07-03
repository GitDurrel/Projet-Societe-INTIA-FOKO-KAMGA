export interface Activite {
  id?: number;
  personnel_id: number;
  titre: string;
  jour_semaine: string;
  heure_debut: string;
  heure_fin: string;
  created_at?: string;
  updated_at?: string;
} 