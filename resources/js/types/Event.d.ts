export interface Event {
  id: string;
  title: string;
  description?: string;
  banner?: string;
  banner_url: string;
  start_time: string; 
  end_time: string;
  registration_deadline: string;
  preliminary_date: string;
  final_date: string;
  whatsapp_group_link: string;
  guidebook_link: string;
  location: string;
  is_online: boolean;
  link_zoom: string;
  quota: number;
  created_at: string;
  updated_at: string;

  start_time_formatted: string;
  end_time_formatted: string;
  registration_deadline_formatted: string;
  preliminary_date_formatted: string;
  final_date_formatted: string;
}