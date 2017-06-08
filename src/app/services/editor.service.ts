import { Injectable } from '@angular/core';
import { Http, Headers } from '@angular/http';
import 'rxjs/add/operator/toPromise';

import { Editor } from '../models/editor';

@Injectable()
export class EditorService {

  private editorsUrl = location.protocol + '//' + location.hostname + '/api/rest.php/editors';
  private headers = new Headers({
    'Content-Type': 'application/json',
    'Authorization': window['xsrfToken']
  });

  constructor(private http: Http) { }

  getEditors(): Promise<Editor[]> {
    return this.http.get(this.editorsUrl)
      .toPromise()
      .then(response => {
        return response.json() as Editor[];
      })
      .catch(this.handleError);
  }

  // getEditor(id: number): Promise<Editor> {
  //   return this.getEditors().then(editors => editors.find(editor => editor.id === id));
  // }

  create(email: string): Promise<Editor> {
    return this.http
      .post(this.editorsUrl, JSON.stringify({ email: email }), { headers: this.headers })
      .toPromise()
      .then(res => res.json())
      .catch(this.handleError);
  }

  // update(editor: Editor): Promise<Editor> {
  //   const url = `${this.editorsUrl}/${editor.id}`;
  //   return this.http.put(url, JSON.stringify(editor), { headers: this.headers })
  //     .toPromise()
  //     .then(() => editor)
  //     .catch(this.handleError);
  // }

  delete(id: number): Promise<void> {
    const url = `${this.editorsUrl}/${id}`;
    return this.http.delete(url, { headers: this.headers })
      .toPromise()
      .then(() => null)
      .catch(this.handleError);
  }

  private handleError(error: any): Promise<any> {
    return Promise.reject(error.message || error);
  }
}
