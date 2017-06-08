import { Injectable } from '@angular/core';
import { Http, Response } from '@angular/http';
import { Observable } from 'rxjs';

import { Editor } from '../../models/editor';

@Injectable()
export class EditorSearchService {

  constructor(private http: Http) { }

  search(term: string): Observable<Editor[]> {
    return this.http
      .get(`app/editors/?name=${term}`)
      .map((r: Response) => r.json().data as Editor[]);
  }
}
