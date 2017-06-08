/* tslint:disable:no-unused-variable */

import { TestBed, async, inject } from '@angular/core/testing';
import { EditorSearchService } from './editor-search.service';

describe('Service: EditorSearch', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [EditorSearchService]
    });
  });

  it('should ...', inject([EditorSearchService], (service: EditorSearchService) => {
    expect(service).toBeTruthy();
  }));
});
