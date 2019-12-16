import { TestBed } from '@angular/core/testing';

import { FluxRSSService } from './flux-rss.service';

describe('FluxRSSService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: FluxRSSService = TestBed.get(FluxRSSService);
    expect(service).toBeTruthy();
  });
});
